<v-dynamic-view {{ $attributes }} > </v-dynamic-view>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dynamic-view-template"
    >
        <div v-html="htmlContent" v-if="htmlContent"></div>
    </script>
    <script type="module">
        app.component('v-dynamic-view', {
            template: '#v-dynamic-view-template',

            props: {
                view: {
                    type: String,
                    required: true
                }
            },

            data() {
                return {
                    htmlContent: '',
                    fetched: false,
                };
            },

            mounted() {
                window.addEventListener('mousemove', this.handleMouseMove);
                window.addEventListener('touchstart', this.handleUserInteraction, { passive: true });
            },

            beforeUnmount() {
                window.removeEventListener('mousemove', this.handleMouseMove);
                window.removeEventListener('touchstart', this.handleUserInteraction);
            },

            methods: {
                handleMouseMove() {
                    if (!this.fetched) {
                        this.fetched = true;
                        this.fetchContent();
                        window.removeEventListener('mousemove', this.handleMouseMove);
                        window.removeEventListener('touchstart', this.handleUserInteraction);
                    }
                },

                fetchContent() {
                    fetch(`/esi?tag=${encodeURIComponent(this.view)}`, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Failed to fetch ESI content: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(html => {
                        this.htmlContent = html;
                    })
                    .catch(error => {
                        console.error(error);

                        this.htmlContent = '<p>Unable to load content. Please try again later.</p>';
                    });
                }
            }
        });
    </script>
@endPushOnce
