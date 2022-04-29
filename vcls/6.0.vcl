vcl 4.1;

import std;
import directors;

backend server1 {
  .host = "127.0.0.1";
  .port = "8080";
  .max_connections = 300;
  .probe = {
    .request =
      "HEAD / HTTP/1.1"
      "Host: localhost"
      "Connection: close"
      "User-Agent: Varnish Health Probe";

    .interval  = 5s;
    .timeout   = 1s;
    .window    = 5;
    .threshold = 3;
  }
  .first_byte_timeout = 300s;
  .connect_timeout = 5s;
  .between_bytes_timeout = 2s;
}

acl purge {
  "localhost";
  "127.0.0.1";
  "::1";
}

sub vcl_init {
  new vdir = directors.round_robin();
  vdir.add_backend(server1);
}

sub vcl_recv {
  set req.backend_hint = vdir.backend();

  if (req.http.Host) {
    set req.http.Host = regsub(req.http.Host, ":[0-9]+", "");
  }

  unset req.http.proxy;

  set req.url = std.querysort(req.url);

  if (req.method == "PURGE") {
    if (!client.ip ~ purge) {
      return (synth(405, "This IP is not allowed to send PURGE requests."));
    }

    return (purge);
  }

  if (req.method != "GET" &&
      req.method != "HEAD" &&
      req.method != "PUT" &&
      req.method != "POST" &&
      req.method != "TRACE" &&
      req.method != "OPTIONS" &&
      req.method != "PATCH" &&
      req.method != "DELETE") {
    return (pipe);
  }

  if (req.http.Upgrade ~ "(?i)websocket") {
    return (pipe);
  }

  if (req.method != "GET" && req.method != "HEAD") {
    return (pass);
  }

  if (req.url ~ "(\?|&)(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=") {
    set req.url = regsuball(req.url, "&(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=([A-z0-9_\-\.%25]+)", "");
    set req.url = regsuball(req.url, "\?(utm_source|utm_medium|utm_campaign|utm_content|gclid|cx|ie|cof|siteurl)=([A-z0-9_\-\.%25]+)", "?");
    set req.url = regsub(req.url, "\?&", "?");
    set req.url = regsub(req.url, "\?$", "");
  }

  if (req.url ~ "\#") {
    set req.url = regsub(req.url, "\#.*$", "");
  }

  if (req.url ~ "\?$") {
    set req.url = regsub(req.url, "\?$", "");
  }

  set req.http.Cookie = regsuball(req.http.Cookie, "has_js=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "__utm.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "_ga=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "_gat=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmctr=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmcmd.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmccn.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "__gads=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "__qc.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "__atuv.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "^;\s*", "");

  if (req.http.cookie ~ "^\s*$") {
    unset req.http.cookie;
  }

  if (req.url ~ "^[^?]*\.(7z|avi|bz2|flac|flv|gz|mka|mkv|mov|mp3|mp4|mpeg|mpg|ogg|ogm|opus|rar|tar|tgz|tbz|txz|wav|webm|xz|zip)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  if (req.url ~ "^[^?]*\.(7z|avi|bmp|bz2|css|csv|doc|docx|eot|flac|flv|gif|gz|ico|jpeg|jpg|js|less|mka|mkv|mov|mp3|mp4|mpeg|mpg|odt|otf|ogg|ogm|opus|pdf|png|ppt|pptx|rar|rtf|svg|svgz|swf|tar|tbz|tgz|ttf|txt|txz|wav|webm|webp|woff|woff2|xls|xlsx|xml|xz|zip)(\?.*)?$") {
    unset req.http.Cookie;
    return (hash);
  }

  set req.http.Surrogate-Capability = "key=ESI/1.0";

  if (req.http.Authorization) {
    return (pass);
  }

  if (
    req.url ~ "/mini-cart" ||
    req.url ~ "/items-count" ||
    req.url ~ "/category-details" ||
    req.url ~ "/comparison" ||
    req.url ~ "/checkout"
  ) {
    return (pass);
  }

  return (hash);
}

sub vcl_pipe {
  if (req.http.upgrade) {
    set bereq.http.upgrade = req.http.upgrade;
  }

  return (pipe);
}

sub vcl_pass {
}

sub vcl_hash {
  hash_data(req.url);

  if (req.http.host) {
    hash_data(req.http.host);
  } else {
    hash_data(server.ip);
  }

  if (req.http.Cookie) {
    hash_data(req.http.Cookie);
  }

  if (req.http.X-Forwarded-Proto) {
    hash_data(req.http.X-Forwarded-Proto);
  }
}

sub vcl_hit {
  if (obj.ttl >= 0s) {
    return (deliver);
  }

  if (std.healthy(req.backend_hint)) {
    if (obj.ttl + 10s > 0s) {
      return (deliver);
    }
  } else {
    if (obj.ttl + obj.grace > 0s) {
      return (deliver);
    }
  }
}

sub vcl_miss {
  return (fetch);
}

sub vcl_backend_response {
  if (
    bereq.url ~ "/?locale=" ||
    bereq.url ~ "/?currency="
  ) {
    return (pass);
  }

  if (beresp.http.X-Pass ~ "YES" || beresp.http.X-Ajax ~ "YES") {
    return (pass);
  }

  if (beresp.http.X-Cacheable ~ "YES") {
    unset beresp.http.set-cookie;
    set beresp.do_esi = true;
  }

  if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
    unset beresp.http.Surrogate-Control;
    set beresp.do_esi = true;
  }

  if (bereq.url ~ "^[^?]*\.(7z|avi|bmp|bz2|css|csv|doc|docx|eot|flac|flv|gif|gz|ico|jpeg|jpg|js|less|mka|mkv|mov|mp3|mp4|mpeg|mpg|odt|otf|ogg|ogm|opus|pdf|png|ppt|pptx|rar|rtf|svg|svgz|swf|tar|tbz|tgz|ttf|txt|txz|wav|webm|webp|woff|woff2|xls|xlsx|xml|xz|zip)(\?.*)?$") {
    unset beresp.http.set-cookie;
  }

  if (bereq.url ~ "^[^?]*\.(7z|avi|bz2|flac|flv|gz|mka|mkv|mov|mp3|mp4|mpeg|mpg|ogg|ogm|opus|rar|tar|tgz|tbz|txz|wav|webm|xz|zip)(\?.*)?$") {
    unset beresp.http.set-cookie;
    set beresp.do_stream = true;
  }

  if (beresp.status == 301 || beresp.status == 302) {
    set beresp.http.Location = regsub(beresp.http.Location, ":[0-9]+", "");
  }

  if (beresp.ttl <= 0s || beresp.http.Set-Cookie || beresp.http.Vary == "*") {
    set beresp.ttl = 120s;
    set beresp.uncacheable = true;

    return (deliver);
  }

  if (beresp.status == 500 || beresp.status == 502 || beresp.status == 503 || beresp.status == 504) {
    return (abandon);
  }

  set beresp.grace = 6h;

  return (deliver);
}

sub vcl_deliver {
  if (obj.hits > 0) {
    set resp.http.X-Cache = "HIT";
  } else {
    set resp.http.X-Cache = "MISS";
  }

  set resp.http.X-Cache-Hits = obj.hits;

  unset resp.http.X-Powered-By;
  unset resp.http.Server;
  unset resp.http.X-Drupal-Cache;
  unset resp.http.X-Varnish;
  unset resp.http.Via;
  unset resp.http.Link;
  unset resp.http.X-Generator;

  return (deliver);
}

sub vcl_purge {
  if (req.method == "PURGE") {
    set req.http.X-Purge = "Yes";

    return(restart);
  }
}

sub vcl_synth {
  if (resp.status == 720) {
    set resp.http.Location = resp.reason;
    set resp.status = 301;
  } elseif (resp.status == 721) {
    set resp.http.Location = resp.reason;
    set resp.status = 302;
  }

  return (deliver);
}

sub vcl_fini {
  return (ok);
}