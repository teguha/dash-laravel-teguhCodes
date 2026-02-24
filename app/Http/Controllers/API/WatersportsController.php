<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Notifications\DataCreateNotification;
use App\Notifications\DataUpdateNotification;
use App\Notifications\DataDeleteNotification;
use App\Notifications\RequestApprovalNotification;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class WatersportsController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getCategory()
    {

        $data =
        [
            [
                'id'    => 1,
                'name'  => 'Watersport'
            ],
            [
                'id'    => 2,
                'name'  => 'Snorkeling'
            ],
            [
                'id'    => 3,
                'name'  => 'Another Activity'
            ]
        ];

        return response()->json([
            'status'    => 200,
            'message'   => "success",
            'data'      => $data
        ]);
        // return view('Auth.Role.index'); // blade utama
    }

    public function getPackages() {
        $data = [
            [
            'id'    => 1,
            'name'  => 'Bali Watersport - Parasailing + Jet Ski + Banana Boat',
            'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            'date'  => '08-01-2026',
            'category_type' => 1,
            'promo_code'    => 'Buy1Get1Free',
            'image' => [
                [
                    'id'    => 1,
                    'img'   => "https=>//www.befreetour.com/img/produk/bali-water-sports/bali-water-sports_6243f29e76ca02b9d0e373022c13ce96f2ed06bc.jpg",
                    'name'  => "image 1"
                ],
                [
                    'id'    => 2,
                    'img'   => "https=>//visitbalitour.com/wp-content/uploads/2015/09/bali-jet-ski-bali-tour-2602415296-1666395775840.jpg",
                    'name'  => "image 2"
                ],
                [
                    'id'    => 3,
                    'img'   => "https=>//fundaynusadua.com/wp-content/uploads/2024/08/fdnd-ultimate2.jpg",
                    'name'  => "image 3"
                ]
            ],
            'rates' => [
                [
                    "id"    => 1,
                    "name"  => "Base Rate",
                    "old_price" => 2400000,
                    "price" => 1200000,
                    "disc"  => 50,
                    "unit"  => "Pax",
                    "qty"   => 5
                ],
                [
                    "id"    => 2,
                    "name"  => "Premium Rate",
                    "old_price" => 4800000,
                    "price" => 2400000,
                    "disc"  => 50,
                    'unit'  => "Pax",
                    "qty"   => 5
                ]
            ]
            ],
            [
                'id'    => 2,
                'name'  => 'Bali Watersport + Quad Bike',
                'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
                'date'  => '07-12-2025',
                'category_type' => 1,
                'promo_code'    => 'WaterSport25',
                'image' => [
                    [
                        'id'    => 1,
                        'img'   => "https=>//www.baliskytour.com/images/WatersportsSpaPackages1.jpg",
                        'name'  => "image 1"
                    ],
                    [
                        'id'    => 2,
                        'img'   => "https=>//visitbalitour.com/wp-content/uploads/2015/09/bali-jet-ski-bali-tour-2602415296-1666395775840.jpg",
                        'name'  => "image 2"
                    ],
                    [
                        'id'    => 3,
                        'img'   => "https=>//fundaynusadua.com/wp-content/uploads/2024/08/fdnd-ultimate2.jpg",
                        'name'  => "image 3"
                    ]
                ],
                'rates' => [
                    [
                        "id"    => 1,
                        "name"  => "Base Rate",
                        "old_price" => 2000000,
                        "price" => 1200000,
                        "disc"  => 40,
                        "unit"  => "Pax",
                        "qty"   => 5
                    ],
                ]
            ],
            [
                'id'    => 3,
                'name'  => 'Snorkeling Blue Lagoon + Monkey Bar + Waterfall',
                'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
                'date'  => '08-12-2025',
                'category_type' => 2,
                'promo_code'    => 'BlueLagoonSnorkeling',
                'image' => [
                    [
                        'id'    => 1,
                        'img'   => "https=>//www.travelersuniverse.com/wp-content/uploads/2025/06/1_bali-snorkeling-on-2-spots-with-lunch-and-transport-800x400.jpg",
                        'name'  => "image 1"
                    ],
                    [
                        'id'    => 2,
                        'img'   => "https=>//d18sx48tl6nre5.cloudfront.net/lg_622b14bff92932f85926c9b677db3e81.jpg",
                        'name'  => "image 2"
                    ],
                    [
                        'id'    => 3,
                        'img'   => "https=>//encrypted-tbn0.gstatic.com/images?q=tbn=>ANd9GcRvwblT7-o8AlASATWdxNe6Y46moqwjFnbDwg&s",
                        'name'  => "image 3"
                    ]
                ],
                'rates' => [
                    [
                        "id"    => 1,
                        "name"  => "Base Rate",
                        "old_price" => 2400000,
                        "price" => 1200000,
                        "disc"  => 50,
                        "unit"  => "Pax",
                        "qty"   => 5
                    ],
                    [
                        "id"    => 2,
                        "name"  => "Premium Rate",
                        "old_price" => 4800000,
                        "price" => 2400000,
                        "disc"  => 50,
                        'unit'  => "Pax",
                        "qty"   => 5
                    ]
                ]
            ],
            [
                'id'    => 4,
                'name'  => 'Bali ATV Package',
                'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
                'date'  => '06-12-2025',
                'category_type' => 3,
                'promo_code'    => 'AtvGo25',
                'image' => [
                    [
                        'id'    => 1,
                        'img'   => "https=>//d3uyff779abz3k.cloudfront.net/-bali-tayatha-com-/image/Everything-about-Accidents-in-Bali-ATV-Tour.jpg",
                        'name'  => "image 1"
                    ],
                    [
                        'id'    => 2,
                        'img'   => "https=>//q-xx.bstatic.com/xdata/images/xphoto/800x800/478124266.jpg?k=1a9ef2e74a8cb4caacc374dedebac45ac69414554fb0762d5ca230dc276ddcde&o=",
                        'name'  => "image 2"
                    ],
                    [
                        'id'    => 3,
                        'img'   => "https=>//www.baliquadbiking.com/wp-content/uploads/2023/03/4-Best-ATV-in-Bali-with-Unique-Track.jpg",
                        'name'  => "image 3"
                    ]
                ],
                'rates' => [
                    [
                        "id"    => 1,
                        "name"  => "Base Rate",
                        "old_price" => 2400000,
                        "price" => 1200000,
                        "disc"  => 50,
                        "unit"  => "Pax",
                        "qty"   => 5
                    ],
                    [
                        "id"    => 2,
                        "name"  => "Premium Rate",
                        "old_price" => 4800000,
                        "price" => 2400000,
                        "disc"  => 50,
                        'unit'  => "Pax",
                        "qty"   => 5
                    ]
                ]
            ],
            [
                'id'    => 5,
                'name'  => 'Ayung Rafting',
                'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
                'date'  => '07-12-2025',
                'category_type' => 3,
                'promo_code'    => 'Rafting25',
                'image' => [
                    [
                        'id'    => 1,
                        'img'   => "https=>//www.raftingbali.net/wp-content/uploads/2023/10/River-Rafting-in-Bali-FAQ.jpg",
                        'name'  => "image 1"
                    ],
                    [
                        'id'    => 2,
                        'img'   => "https=>//encrypted-tbn0.gstatic.com/images?q=tbn=>ANd9GcQT9Ov3IF58tjmreou1Vtb6lCe5iLb5VTgdAQ&shttps=>//s-light.tiket.photos/t/01E25EBZS3W0FY9GTG6C42E1SE/rsfit19201280gsm/events/2023/07/05/bfdf8c7d-59ad-4759-85b0-e9808067cd54-1688523953290-a9a065b7d464176adf01e5e7d3e10c6e.jpg",
                        'name'  => "image 2"
                    ],
                    [
                        'id'    => 3,
                        'img'   => "https=>//media.tacdn.com/media/attractions-splice-spp-674x446/06/e6/80/e9.jpg",
                        'name'  => "image 3"
                    ]
                ],
                'rates' => [
                    [
                        "id"    => 1,
                        "name"  => "Base Rate",
                        "old_price" => 2400000,
                        "price" => 1200000,
                        "disc"  => 50,
                        "unit"  => "Pax",
                        "qty"   => 5
                    ],
                    [
                        "id"    => 2,
                        "name"  => "Premium Rate",
                        "old_price" => 2400000,
                        "price" => 500000,
                        "disc"  => 80,
                        'unit'  => "Pax",
                        "qty"   => 5
                    ]
                ]
            ]
        ];

        // $data = [];

        return response()->json([
            'status'    => 200,
            'message'   => 'success',
            'data'      => $data
        ]);
    }

    public function getSearchPackages(Request $request) {
        $dateSearch = $request->dateSearch;
        $promoSearch = $request->promoSearch; 

        // default disimpan dalam bentuk array bukan object
        $data = [
            [
            'id'    => 1,
            'name'  => 'Bali Watersport - Parasailing + Jet Ski + Banana Boat',
            'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            'date'  => '08-01-2026',
            'category_type' => 1,
            'promo_code'    => 'Buy1Get1Free',
            'image' => [
                [
                    'id'    => 1,
                    'img'   => "",
                    'name'  => "image 1"
                ],
                [
                    'id'    => 2,
                    'img'   => "",
                    'name'  => "image 2"
                ],
                [
                    'id'    => 3,
                    'img'   => "",
                    'name'  => "image 3"
                ]
            ],
            'rates' => [
                [
                    "id"    => 1,
                    "name"  => "Base Rate",
                    "old_price" => 2400000,
                    "price" => 1200000,
                    "disc"  => 50,
                    "unit"  => "Pax",
                    "qty"   => 5
                ],
                [
                    "id"    => 2,
                    "name"  => "Premium Rate",
                    "old_price" => 4800000,
                    "price" => 2400000,
                    "disc"  => 50,
                    'unit'  => "Pax",
                    "qty"   => 5
                ]
                ]
            ]
            // ],
            // [
            //     'id'    => 2,
            //     'name'  => 'Bali Watersport + Quad Bike',
            //     'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            //     'date'  => '08-01-2026',
            //     'category_type' => 1,
            //     'promo_code'    => 'WaterSport25',
            //     'image' => [
            //         [
            //             'id'    => 1,
            //             'img'   => "https=>//www.baliskytour.com/images/WatersportsSpaPackages1.jpg",
            //             'name'  => "image 1"
            //         ],
            //         [
            //             'id'    => 2,
            //             'img'   => "https=>//visitbalitour.com/wp-content/uploads/2015/09/bali-jet-ski-bali-tour-2602415296-1666395775840.jpg",
            //             'name'  => "image 2"
            //         ],
            //         [
            //             'id'    => 3,
            //             'img'   => "https=>//fundaynusadua.com/wp-content/uploads/2024/08/fdnd-ultimate2.jpg",
            //             'name'  => "image 3"
            //         ]
            //     ],
            //     'rates' => [
            //         [
            //             "id"    => 1,
            //             "name"  => "Base Rate",
            //             "old_price" => 2000000,
            //             "price" => 1200000,
            //             "disc"  => 40,
            //             "unit"  => "Pax",
            //             "qty"   => 5
            //         ],
            //     ]
            // ],
            // [
            //     'id'    => 3,
            //     'name'  => 'Snorkeling Blue Lagoon + Monkey Bar + Waterfall',
            //     'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            //     'date'  => '08-12-2025',
            //     'category_type' => 2,
            //     'promo_code'    => 'BlueLagoonSnorkeling',
            //     'image' => [
            //         [
            //             'id'    => 1,
            //             'img'   => "https=>//www.travelersuniverse.com/wp-content/uploads/2025/06/1_bali-snorkeling-on-2-spots-with-lunch-and-transport-800x400.jpg",
            //             'name'  => "image 1"
            //         ],
            //         [
            //             'id'    => 2,
            //             'img'   => "https=>//d18sx48tl6nre5.cloudfront.net/lg_622b14bff92932f85926c9b677db3e81.jpg",
            //             'name'  => "image 2"
            //         ],
            //         [
            //             'id'    => 3,
            //             'img'   => "https=>//encrypted-tbn0.gstatic.com/images?q=tbn=>ANd9GcRvwblT7-o8AlASATWdxNe6Y46moqwjFnbDwg&s",
            //             'name'  => "image 3"
            //         ]
            //     ],
            //     'rates' => [
            //         [
            //             "id"    => 1,
            //             "name"  => "Base Rate",
            //             "old_price" => 2400000,
            //             "price" => 1200000,
            //             "disc"  => 50,
            //             "unit"  => "Pax",
            //             "qty"   => 5
            //         ],
            //         [
            //             "id"    => 2,
            //             "name"  => "Premium Rate",
            //             "old_price" => 4800000,
            //             "price" => 2400000,
            //             "disc"  => 50,
            //             'unit'  => "Pax",
            //             "qty"   => 5
            //         ]
            //     ]
            // ],
            // [
            //     'id'    => 4,
            //     'name'  => 'Bali ATV Package',
            //     'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            //     'date'  => '06-12-2025',
            //     'category_type' => 3,
            //     'promo_code'    => 'AtvGo25',
            //     'image' => [
            //         [
            //             'id'    => 1,
            //             'img'   => "https=>//d3uyff779abz3k.cloudfront.net/-bali-tayatha-com-/image/Everything-about-Accidents-in-Bali-ATV-Tour.jpg",
            //             'name'  => "image 1"
            //         ],
            //         [
            //             'id'    => 2,
            //             'img'   => "https=>//q-xx.bstatic.com/xdata/images/xphoto/800x800/478124266.jpg?k=1a9ef2e74a8cb4caacc374dedebac45ac69414554fb0762d5ca230dc276ddcde&o=",
            //             'name'  => "image 2"
            //         ],
            //         [
            //             'id'    => 3,
            //             'img'   => "https=>//www.baliquadbiking.com/wp-content/uploads/2023/03/4-Best-ATV-in-Bali-with-Unique-Track.jpg",
            //             'name'  => "image 3"
            //         ]
            //     ],
            //     'rates' => [
            //         [
            //             "id"    => 1,
            //             "name"  => "Base Rate",
            //             "old_price" => 2400000,
            //             "price" => 1200000,
            //             "disc"  => 50,
            //             "unit"  => "Pax",
            //             "qty"   => 5
            //         ],
            //         [
            //             "id"    => 2,
            //             "name"  => "Premium Rate",
            //             "old_price" => 4800000,
            //             "price" => 2400000,
            //             "disc"  => 50,
            //             'unit'  => "Pax",
            //             "qty"   => 5
            //         ]
            //     ]
            // ],
            // [
            //     'id'    => 5,
            //     'name'  => 'Ayung Rafting',
            //     'desc'  => 'Welcome to an all-inclusive Bali adventure, combining the excitment of watersport, the beauty of Padang Padang Beach and the splendor of Uluwatu Temple',
            //     'date'  => '07-12-2025',
            //     'category_type' => 3,
            //     'promo_code'    => 'Rafting25',
            //     'image' => [
            //         [
            //             'id'    => 1,
            //             'img'   => "",
            //             'name'  => "image 1"
            //         ],
            //         [
            //             'id'    => 2,
            //             'img'   => "https=>//encrypted-tbn0.gstatic.com/images?q=tbn=>ANd9GcQT9Ov3IF58tjmreou1Vtb6lCe5iLb5VTgdAQ&shttps=>//s-light.tiket.photos/t/01E25EBZS3W0FY9GTG6C42E1SE/rsfit19201280gsm/events/2023/07/05/bfdf8c7d-59ad-4759-85b0-e9808067cd54-1688523953290-a9a065b7d464176adf01e5e7d3e10c6e.jpg",
            //             'name'  => "image 2"
            //         ],
            //         [
            //             'id'    => 3,
            //             'img'   => "https=>//media.tacdn.com/media/attractions-splice-spp-674x446/06/e6/80/e9.jpg",
            //             'name'  => "image 3"
            //         ]
            //     ],
            //     'rates' => [
            //         [
            //             "id"    => 1,
            //             "name"  => "Base Rate",
            //             "old_price" => 2400000,
            //             "price" => 1200000,
            //             "disc"  => 50,
            //             "unit"  => "Pax",
            //             "qty"   => 5
            //         ],
            //         [
            //             "id"    => 2,
            //             "name"  => "Premium Rate",
            //             "old_price" => 2400000,
            //             "price" => 500000,
            //             "disc"  => 80,
            //             'unit'  => "Pax",
            //             "qty"   => 5
            //         ]
            //     ]
            // ]
        ];

        $res = [];
        foreach($data as $d){
            if($dateSearch == $d['date']){
                $res[] = $d;
            }
        }

        return response()->json([
            "data"      => $res,
            'message'   => "success",
            "status"    => 200
        ]);
    }

    // get data


}
