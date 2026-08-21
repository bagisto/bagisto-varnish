# VCL version 5.0 is not supported so it should be 4.0 even though actually used Varnish version is 6
vcl 4.0;

import std;
# The minimal Varnish version is 6.0
# For SSL offloading, pass the following header in your proxy server or load balancer: 'X-SSL-Offloaded: https'

backend default {
    .host = "127.0.0.1";
    .port = "8080";
    .first_byte_timeout = 600s;
    .probe = {
        .timeout = 2s;
        .interval = 5s;
        .window = 10;
        .threshold = 5;
   }
}

acl purge {
 "localhost";
 "127.0.0.1";
 "::1";
}

sub vcl_recv {
    if (req.restarts > 0) {
        set req.hash_always_miss = true;
    }

    if (req.method == "PURGE") {
        if (client.ip !~ purge) {
            return (synth(405, "Method not allowed"));
        }
        # To use the X-Pool header for purging varnish during automated deployments, make sure the X-Pool header
        # has been added to the response in your backend server config. This is used, for example, by the
        # capistrano-bagisto gem for purging old content from varnish during it's deploy routine.
        if (!req.http.X-Bagisto-Purge-All && !req.http.X-Bagisto-Tags-Pattern && !req.http.X-Pool) {
            return (synth(400, "X-Bagisto-Tags-Pattern, X-Bagisto-Purge-All or X-Pool header required"));
        }
        # Every cached object carries X-Bagisto-Url, so a full purge also reaches the ones
        # Bagisto never tagged: static files, ESI fragments and any route that does not run
        # the cache.response middleware. Banning on X-Bagisto-Tags would leave those behind.
        if (req.http.X-Bagisto-Purge-All) {
          ban("obj.http.X-Bagisto-Url ~ .");
          return (synth(200, "Purged"));
        }
        if (req.http.X-Bagisto-Tags-Pattern) {
          ban("obj.http.X-Bagisto-Tags ~ " + req.http.X-Bagisto-Tags-Pattern);
        }
        if (req.http.X-Pool) {
          ban("obj.http.X-Pool ~ " + req.http.X-Pool);
        }
        return (synth(200, "Purged"));
    }

    if (req.method != "GET" &&
        req.method != "HEAD" &&
        req.method != "PUT" &&
        req.method != "POST" &&
        req.method != "TRACE" &&
        req.method != "OPTIONS" &&
        req.method != "PATCH" &&
        req.method != "DELETE") {
          /* Non-RFC2616 or CONNECT which is weird. */
          return (pipe);
    }

    # We only deal with GET and HEAD by default
    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }

    # Bypass customer, checkout
    if (req.url ~ "/customer" || req.url ~ "/checkout") {
        return (pass);
    }

    # Bypass the API of products 
    if (req.url ~ "/api/products") {
        return (hash);	
    }

    # Set initial grace period usage status
    set req.http.grace = "3d";

    # normalize url in case of leading HTTP scheme and domain
    set req.url = regsub(req.url, "^http[s]?://", "");

    # collect all cookies
    std.collect(req.http.Cookie);

    # Remove all marketing get parameters to minimize the cache objects
    if (req.url ~ "(\?|&)(gad_source|gbraid|wbraid|_gl|dclid|gclsrc|srsltid|msclkid|gclid|cx|_kx|ie|cof|siteurl|zanpid|origin|fbclid|mc_[a-z]+|utm_[a-z]+|_bta_[a-z]+)=") {
        set req.url = regsuball(req.url, "(gad_source|gbraid|wbraid|_gl|dclid|gclsrc|srsltid|msclkid|gclid|cx|_kx|ie|cof|siteurl|zanpid|origin|fbclid|mc_[a-z]+|utm_[a-z]+|_bta_[a-z]+)=[-_A-z0-9+()%.]+&?", "");
        set req.url = regsub(req.url, "[?|&]+$", "");
    }

    # Static files caching
    if (req.url ~ "^[^?]*\.(7z|avi|bmp|bz2|css|csv|doc|docx|eot|flac|flv|gif|gz|ico|jpeg|jpg|js|less|mka|mkv|mov|mp3|mp4|mpeg|mpg|odt|otf|ogg|ogm|opus|pdf|png|ppt|pptx|rar|rtf|svg|svgz|swf|tar|tbz|tgz|ttf|txt|txz|wav|webm|webp|woff|woff2|xls|xlsx|xml|xz|zip)(\?.*)?$") {
        # Static files should not be cached by default
        #return (pass);

        # But if you use a few locales and don't use CDN you can enable caching static files by commenting previous line (#return (pass);) and uncommenting next 3 lines
        unset req.http.Https;
        unset req.http.X-SSL-Offloaded;

        unset req.http.Cookie;
    }

    # Bypass authenticated GraphQL requests without a X-Bagisto-Cache-Id
    if (req.url ~ "/graphql" && !req.http.X-Bagisto-Cache-Id && req.http.Authorization ~ "^Bearer") {
        return (pass);
    }

    # Serve storage assets like images directly
    if (req.url ~ "^/storage/") {
        unset req.http.Cookie;
        return (hash);
    }

    return (hash);
}

sub vcl_hash {
    if ((req.url !~ "/graphql" || !req.http.X-Bagisto-Cache-Id) && req.http.cookie ~ "X-Bagisto-Vary=") {
        hash_data(regsub(req.http.cookie, "^.*?X-Bagisto-Vary=([^;]+);*.*$", "\1"));
    }

    # To make sure http users don't see ssl warning
    if (req.http.X-SSL-Offloaded) {
        hash_data(req.http.X-SSL-Offloaded);
    }
    /* {{ design_exceptions_code }} */

    if (req.url ~ "/graphql") {
        call process_graphql_headers;
    }
}

sub process_graphql_headers {
    if (req.http.X-Bagisto-Cache-Id) {
        hash_data(req.http.X-Bagisto-Cache-Id);

        # When the frontend stops sending the auth token, make sure users stop getting results cached for logged-in users
        if (req.http.Authorization ~ "^Bearer") {
            hash_data("Authorized");
        }
    }

    if (req.http.Store) {
        hash_data(req.http.Store);
    }

    if (req.http.Content-Currency) {
        hash_data(req.http.Content-Currency);
    }
}

sub vcl_backend_response {

    set beresp.grace = 3d;
    set beresp.ttl = 1h;

    # Stamped on every object so a full purge has something to ban on even when Bagisto did
    # not tag the response. Taken off again in vcl_deliver, so it never reaches a browser.
    set beresp.http.X-Bagisto-Url = bereq.url;

    if (beresp.http.content-type ~ "text") {
        set beresp.do_esi = true;
    }

    if (bereq.url ~ "\.js$" || beresp.http.content-type ~ "text") {
        set beresp.do_gzip = true;
    }

    if (beresp.http.X-Bagisto-Debug) {
        set beresp.http.X-Bagisto-Cache-Control = beresp.http.Cache-Control;
    }

    # cache only successfully responses and 404s that are not marked as private
    if ((beresp.status != 200 && beresp.status != 404) || beresp.http.Cache-Control ~ "private") {
        set beresp.uncacheable = true;
        set beresp.ttl = 86400s;
        return (deliver);
    }

    # validate if we need to cache it and prevent from setting cookie
    if (beresp.ttl > 0s && (bereq.method == "GET" || bereq.method == "HEAD")) {
        # Collapse beresp.http.set-cookie in order to merge multiple set-cookie headers
        # Although it is not recommended to collapse set-cookie header,
        # it is safe to do it here as the set-cookie header is removed below
        std.collect(beresp.http.set-cookie);
        # Do not cache the response under current cache key (hash),
        # if the response has X-Bagisto-Vary but the request does not.
        if ((bereq.url !~ "/graphql" || !bereq.http.X-Bagisto-Cache-Id)
         && bereq.http.cookie !~ "X-Bagisto-Vary="
         && beresp.http.set-cookie ~ "X-Bagisto-Vary=") {
           set beresp.ttl = 0s;
           set beresp.uncacheable = true;
        }
        unset beresp.http.set-cookie;
    }

    # If page is not cacheable then bypass varnish for 2 minutes as Hit-For-Pass
    if (beresp.ttl <= 0s ||
        beresp.http.Surrogate-control ~ "no-store" ||
        (!beresp.http.Surrogate-Control &&
        beresp.http.Cache-Control ~ "no-cache|no-store") ||
        beresp.http.Vary == "*") {
        # Mark as Hit-For-Pass for the next 2 minutes
        set beresp.ttl = 120s;
        set beresp.uncacheable = true;
    }

    # If the cache key in the Bagisto response doesn't match the one that was sent in the request, don't cache under the request's key
    if (bereq.url ~ "/graphql" && bereq.http.X-Bagisto-Cache-Id && bereq.http.X-Bagisto-Cache-Id != beresp.http.X-Bagisto-Cache-Id) {
        set beresp.ttl = 0s;
        set beresp.uncacheable = true;
    }

    if (bereq.url ~ "^/api/products") {
 	 set beresp.ttl = 60s;
	 set beresp.http.Cache-Control = "public, max-age=60";
    }

    if (bereq.url ~ "\.(js|css|png|jpg|jpeg|gif|svg|webp|woff2?|ttf|eot)$") {
        set beresp.ttl = 365d;
        set beresp.http.Cache-Control = "public, max-age=31536000, immutable";
    }

    return (deliver);
}

sub vcl_deliver {
    # Internal to the ban above. Unsetting it here leaves it on the stored object.
    unset resp.http.X-Bagisto-Url;

    if (obj.uncacheable) {
        set resp.http.X-Bagisto-Cache-Debug = "UNCACHEABLE";
    } else if (obj.hits) {
        set resp.http.X-Bagisto-Cache-Debug = "HIT";
        set resp.http.Grace = req.http.grace;
    } else {
        set resp.http.X-Bagisto-Cache-Debug = "MISS";
    }

    # Not letting browser to cache non-static files.
    if (resp.http.Cache-Control !~ "private" && req.url !~ "^[^?]*\.(7z|avi|bz2|flac|flv|gz|mka|mkv|mov|mp3|mp4|mpeg|mpg|ogg|ogm|opus|rar|tar|tgz|tbz|txz|wav|webm|xz|zip)(\?.*)?$") {
  #      set resp.http.Pragma = "no-cache";
 #       set resp.http.Expires = "-1";
#        set resp.http.Cache-Control = "no-store, no-cache, must-revalidate, max-age=0";
    }

    if (!resp.http.X-Bagisto-Debug) {
    #    unset resp.http.Age;
    }
#    unset resp.http.X-Bagisto-Debug;
 #   unset resp.http.X-Bagisto-Tags;
#    unset resp.http.X-Powered-By;
#    unset resp.http.Server;
#    unset resp.http.X-Varnish;
 #   unset resp.http.Via;
#    unset resp.http.Link;
}

sub vcl_hit {
    if (obj.ttl >= 0s) {
        # Hit within TTL period
        return (deliver);
    }
    if (std.healthy(req.backend_hint)) {
        if (obj.ttl + 30s > 0s) {
            # Hit after TTL expiration, but within grace period
            set req.http.grace = "normal (healthy server)";
            return (deliver);
        } else {
            # Hit after TTL and grace expiration
            return (restart);
        }
    } else {
        # server is not healthy, retrieve from cache
        set req.http.grace = "unlimited (unhealthy server)";
        return (deliver);
    }
}
