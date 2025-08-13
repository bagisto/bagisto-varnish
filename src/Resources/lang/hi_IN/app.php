<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'फुल पेज कैश',
            'info'          => 'बेहतर प्रदर्शन के लिए फुल पेज कैश सेटिंग्स कॉन्फ़िगर करें।',
            'configuration' => [
                'title' => 'कॉन्फ़िगरेशन',
                'info'  => 'कैश एप्लिकेशन और संबंधित सेटिंग्स प्रबंधित करें।',
                'fpc'   => [
                    'title'             => 'कैश एप्लिकेशन',
                    'info'              => 'अपने स्टोर के लिए उपयोग करने के लिए कैश एप्लिकेशन चुनें।',
                    'cache_application' => [
                        'title'   => 'कैश एप्लिकेशन',
                        'varnish' => [
                            'title'       => 'Varnish (सिफारिश की गई)',
                            'info'        => 'प्रदर्शन बढ़ाने के लिए Varnish कैशिंग सक्षम करें।',
                            'access_list' => [
                                'title' => 'एक्सेस लिस्ट',
                                'info'  => 'Varnish सर्वर तक पहुँचने की अनुमति प्राप्त कॉमा सेparated IP एड्रेस।',
                            ],
                            'url' => [
                                'title' => 'Varnish होस्ट URL',
                                'info'  => 'अपने Varnish सर्वर का URL या IP दर्ज करें।',
                            ],
                            'backend_url' => [
                                'title' => 'Backend होस्ट URL',
                                'info'  => 'उस backend सर्वर का IP या hostname दर्ज करें जिससे Varnish जुड़ता है (जैसे localhost)।',
                            ],
                            'backend_port' => [
                                'title' => 'Backend होस्ट पोर्ट',
                                'info'  => 'उस पोर्ट को दर्ज करें जिस पर backend सर्वर चल रहा है (डिफ़ॉल्ट: 8080)।',
                            ],
                            'grace_period' => [
                                'title' => 'Grace अवधि',
                                'info'  => 'जब backend धीमा या अनुपलब्ध हो तो Varnish कितना समय पुराना कंटेंट सर्व करे।',
                            ],
                            'export_vcl' => [
                                'title' => 'VCL निर्यात (6.0)',
                                'info'  => 'फ़ाइल को स्थानीय रूप से या /etc/varnish/default.vcl में सहेजें। फिर Varnish सेवा पुनः प्रारंभ करें।',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'कैश प्रबंधन',
                'info'        => 'कैश किए गए कंटेंट का प्रबंधन और पर्ज करें।',
                'purge_cache' => [
                    'title'       => 'कैश साफ़ करें',
                    'info'        => 'अपने स्टोर के लिए कैश साफ़ करें।',
                    'via_url'     => [
                        'title'        => 'URLs द्वारा पर्ज करें',
                        'btn'          => 'पर्ज करें',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'स्पेसिफिक कैश एंट्रीज़ को हटाने के लिए पूर्ण URL कॉमा से अलग दर्ज करें। पाथ और डोमेन बिल्कुल मेल खाना चाहिए।',
                        'confirmation' => 'क्या आप निश्चित हैं कि आप निर्दिष्ट URLs के लिए कैश पर्ज करना चाहते हैं?',
                    ],
                    'success'         => 'कैश सफलतापूर्वक पर्ज किया गया: :urls',
                    'failure'         => 'कैश पर्ज करने में विफल: :urls',
                    'all_success'     => 'सभी कैश सफलतापूर्वक पर्ज किए गए।',
                    'partial_failure' => 'निम्नलिखित URLs के लिए पर्ज असफल: :urls',
                    'exception'       => 'अपवाद हुआ: :message',
                    'url_required'    => 'पर्ज URL आवश्यक है।',
                    'unknown_url'     => 'अज्ञात URL',
                ],
                'purge_full_cache' => [
                    'title'        => 'सभी पर्ज करें',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'सभी पर्ज करें',
                    'info'         => 'यह Varnish के सभी कैश एंट्रीज़ को साफ़ करेगा। सावधानी से उपयोग करें क्योंकि यह अस्थायी रूप से प्रदर्शन को प्रभावित कर सकता है।',
                    'confirmation' => 'क्या आप सुनिश्चित हैं कि आप पूरा कैश पर्ज करना चाहते हैं? इससे सभी कैश कंटेंट हट जाएगा और प्रदर्शन अस्थायी रूप से प्रभावित हो सकता है।',
                ],
            ],
        ],
    ],
];
