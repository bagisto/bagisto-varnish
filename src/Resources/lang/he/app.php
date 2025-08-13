<?php

return [
    'configuration' => [
        'fpc' => [
            'title'         => 'מטמון עמוד מלא',
            'info'          => 'הגדר את הגדרות המטמון לעמוד מלא לשיפור הביצועים.',
            'configuration' => [
                'title' => 'הגדרות',
                'info'  => 'ניהול יישום המטמון והגדרות קשורות.',
                'fpc'   => [
                    'title'             => 'יישום מטמון',
                    'info'              => 'בחר את יישום המטמון לשימוש בחנות שלך.',
                    'cache_application' => [
                        'title'   => 'יישום מטמון',
                        'varnish' => [
                            'title'       => 'Varnish (מומלץ)',
                            'info'        => 'אפשר מטמון Varnish לשיפור הביצועים.',
                            'access_list' => [
                                'title' => 'רשימת גישה',
                                'info'  => 'כתובות IP מופרדות בפסיקים המורשות לגשת לשרת Varnish.',
                            ],
                            'url' => [
                                'title' => 'כתובת מארח Varnish',
                                'info'  => 'הזן את ה-URL או כתובת ה-IP של שרת Varnish שלך.',
                            ],
                            'backend_url' => [
                                'title' => 'כתובת מארח Backend',
                                'info'  => 'הזן את כתובת ה-IP או שם המארח של שרת ה-Backend שאליו Varnish מתחבר (למשל localhost).',
                            ],
                            'backend_port' => [
                                'title' => 'פורט מארח Backend',
                                'info'  => 'הזן את הפורט שעליו שרת ה-Backend פועל (ברירת מחדל: 8080).',
                            ],
                            'grace_period' => [
                                'title' => 'תקופת חסד',
                                'info'  => 'הגדר כמה זמן Varnish ישמש תוכן ישן כאשר ה-Backend איטי או אינו זמין.',
                            ],
                            'export_vcl' => [
                                'title' => 'ייצוא VCL (6.0)',
                                'info'  => 'שמור את הקובץ מקומית או ב-/etc/varnish/default.vcl. לאחר מכן הפעל מחדש את שירות Varnish.',
                            ],
                        ],
                    ],
                ],
            ],
            'cache_management' => [
                'title'       => 'ניהול מטמון',
                'info'        => 'נהל ונקה תוכן שמור במטמון.',
                'purge_cache' => [
                    'title'       => 'ניקוי מטמון',
                    'info'        => 'נקה את המטמון של החנות שלך.',
                    'via_url'     => [
                        'title'        => 'ניקוי לפי URLים',
                        'btn'          => 'נקה',
                        'placeholder'  => 'https://www.example.com, https://www.example.com/cat.jpg',
                        'info'         => 'הזן כתובות URL מלאות מופרדות בפסיקים כדי לנקות רשומות מטמון ספציפיות מ-Varnish. הנתיב והדומיין חייבים להתאים במדויק.',
                        'confirmation' => 'האם אתה בטוח שברצונך לנקות את המטמון עבור כתובות ה-URL שצוינו?',
                    ],
                    'success'         => 'המטמון נוקה בהצלחה עבור: :urls',
                    'failure'         => 'נכשל בניקוי המטמון עבור: :urls',
                    'all_success'     => 'כל המטמון נוקה בהצלחה.',
                    'partial_failure' => 'נכשל בניקוי כתובות ה-URL הבאות: :urls',
                    'exception'       => 'אירעה חריגה: :message',
                    'url_required'    => 'דרוש URL לניקוי.',
                    'unknown_url'     => 'כתובת URL לא ידועה',
                ],
                'purge_full_cache' => [
                    'title'        => 'נקה הכל',
                    'placeholder'  => 'https://www.example.com/cat.jpg/foo/bar',
                    'btn'          => 'נקה הכל',
                    'info'         => 'זה ינקה את כל רשומות המטמון של Varnish. השתמש בזהירות שכן זה עשוי להשפיע זמנית על הביצועים.',
                    'confirmation' => 'האם אתה בטוח שברצונך לנקות את כל המטמון? פעולה זו תסיר את כל התוכן שנשמר במטמון ועלולה להשפיע זמנית על הביצועים.',
                ],
            ],
        ],
    ],
];
