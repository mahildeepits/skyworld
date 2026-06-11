<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getData();
        foreach ($data as $item) {
            $orderDetails = generateRandomOrderData();
            Task::updateOrCreate([
                'title' => $item['category'],
                'product_type' => $item['subCategory'],
            ],[
                'title_description' => 'Compare the best campanies in this category',
                'order_date' => $orderDetails['order_date'],
                'order_number' => $orderDetails['order_no'],
                'status' => 1,
                'reviews' => $item['reviews'],
            ]);
        }
    }
    public function getData(){
        return [
            [
                "category" => "Home & Garden",
                "subCategory" => "Furniture Store",
                "reviews" => [
                    "Absolutely love this product! The quality exceeded my expectations.",
                    "You can tell it's made from high-quality materials. Worth every penny.",
                    "Design is beautiful and functionality is spot on.",
                    "After using it for a week, I’m really impressed with the performance."
                ]
            ],
            [
                "category" => "Home & Garden",
                "subCategory" => "Garden Supplies",
                "reviews" => [
                    "Great customer service and the product feels premium.",
                    "Installation was quick and simple. Instructions were clear.",
                    "Blends seamlessly with my other items. Very happy with this.",
                    "This is one of the best purchases I’ve made this year."
                ]
            ],
            [
                "category" => "Home & Garden",
                "subCategory" => "Home Decor",
                "reviews" => [
                    "Works perfectly and looks great in any setting. Very satisfied!",
                    "Design is beautiful and functionality is spot on.",
                    "Absolutely love this product! The quality exceeded my expectations.",
                    "It has definitely made my daily routine much easier and enjoyable."
                ]
            ],
            [
                "category" => "Home & Garden",
                "subCategory" => "Lighting Fixtures",
                "reviews" => [
                    "Fantastic build and attention to detail. Would buy again.",
                    "Arrived on time and was packaged very securely. Top notch.",
                    "Easy to use and extremely effective. Would recommend to friends.",
                    "Highly recommend it to anyone looking for both style and function."
                ]
            ],
            [
                "category" => "Home & Garden",
                "subCategory" => "Kitchenware",
                "reviews" => [
                    "Blends seamlessly with my other items. Very happy with this.",
                    "Great customer service and the product feels premium.",
                    "This is one of the best purchases I’ve made this year.",
                    "You can tell it's made from high-quality materials. Worth every penny."
                ]
            ],
            [
                "category" => "Electronics",
                "subCategory" => "Mobile Phones",
                "reviews" => [
                    "Absolutely love this product! The quality exceeded my expectations.",
                    "After using it for a week, I’m really impressed with the performance.",
                    "You can tell it's made from high-quality materials. Worth every penny.",
                    "Highly recommend it to anyone looking for both style and function."
                ]
            ],
            [
                "category" => "Electronics",
                "subCategory" => "Laptops",
                "reviews" => [
                    "Design is beautiful and functionality is spot on.",
                    "Easy to use and extremely effective. Would recommend to friends.",
                    "Blends seamlessly with my other items. Very happy with this.",
                    "Fantastic build and attention to detail. Would buy again."
                ]
            ],
            [
                "category" => "Electronics",
                "subCategory" => "Tablets",
                "reviews" => [
                    "Arrived on time and was packaged very securely. Top notch.",
                    "This is one of the best purchases I’ve made this year.",
                    "Installation was quick and simple. Instructions were clear.",
                    "It has definitely made my daily routine much easier and enjoyable."
                ]
            ],
            [
                "category" => "Electronics",
                "subCategory" => "Smart Home Devices",
                "reviews" => [
                    "I was pleasantly surprised by how good this turned out to be.",
                    "Design is beautiful and functionality is spot on.",
                    "Great customer service and the product feels premium.",
                    "Works perfectly and looks great in any setting. Very satisfied!"
                ]
            ],
            [
                "category" => "Electronics",
                "subCategory" => "Wearable Technology",
                "reviews" => [
                    "Easy to use and extremely effective. Would recommend to friends.",
                    "Absolutely love this product! The quality exceeded my expectations.",
                    "This is one of the best purchases I’ve made this year.",
                    "Fantastic build and attention to detail. Would buy again."
                ]
            ],
            [
                "category" => "Beauty & Personal Care",
                "subCategory" => "Skincare",
                "reviews" => [
                    "Feels refreshing on the skin and gives lasting results.",
                    "Packaging is elegant and the formula is top quality.",
                    "Definitely helped improve my skincare routine noticeably.",
                    "Love the scent and the way it makes my skin feel smooth."
                ]
            ],
            [
                "category" => "Beauty & Personal Care",
                "subCategory" => "Hair Care",
                "reviews" => [
                    "Leaves my hair soft, shiny, and healthy-looking every time.",
                    "This product has become a staple in my daily routine.",
                    "Salon-quality results at home — I'm impressed!",
                    "Smells amazing and does exactly what it promises."
                ]
            ],
            [
                "category" => "Beauty & Personal Care",
                "subCategory" => "Makeup",
                "reviews" => [
                    "Goes on smoothly and lasts all day without smudging.",
                    "Perfect for sensitive skin and looks natural yet polished.",
                    "Color payoff is vibrant and consistent. Great value.",
                    "It made a real difference in my makeup game!"
                ]
            ],
            [
                "category" => "Beauty & Personal Care",
                "subCategory" => "Fragrances",
                "reviews" => [
                    "Elegant and long-lasting — I've received many compliments.",
                    "My new signature scent! Subtle and sophisticated.",
                    "Great for both daily use and special occasions.",
                    "The scent lingers just enough — very classy."
                ]
            ],
            [
                "category" => "Beauty & Personal Care",
                "subCategory" => "Nail Care",
                "reviews" => [
                    "My nails have never looked this good. Highly recommend!",
                    "Easy to apply and dries quickly without smudging.",
                    "Professional results in the comfort of my home.",
                    "Love the shine and durability of this product."
                ]
            ],
            [
                "category" => "Fashion",
                "subCategory" => "Men's Clothing",
                "reviews" => [
                    "Excellent fit and stylish. Got a lot of compliments!",
                    "Comfortable fabric and good stitching quality.",
                    "Looks just like the pictures. Very happy with the purchase.",
                    "A wardrobe essential. Will be buying more."
                ]
            ],
            [
                "category" => "Fashion",
                "subCategory" => "Women's Clothing",
                "reviews" => [
                    "True to size and flattering design. I feel great in it!",
                    "So soft and comfortable — I don’t want to take it off.",
                    "Great quality fabric and beautiful patterns.",
                    "Stylish and well-made. Totally worth it!"
                ]
            ],
            [
                "category" => "Fashion",
                "subCategory" => "Kids' Clothing",
                "reviews" => [
                    "Perfect fit for my child and durable too.",
                    "Cute design and very easy to wash and maintain.",
                    "My kid loves it — and so do I!",
                    "Great price for high-quality kids' apparel."
                ]
            ],
            [
                "category" => "Fashion",
                "subCategory" => "Footwear",
                "reviews" => [
                    "Super comfortable and goes well with any outfit.",
                    "Durable sole and perfect arch support. Love them!",
                    "Stylish and functional. I wear them daily.",
                    "Exceeded my expectations in comfort and design."
                ]
            ],
            [
                "category" => "Fashion",
                "subCategory" => "Accessories",
                "reviews" => [
                    "Well-made and adds a perfect touch to my outfits.",
                    "Exactly what I was looking for. Great value too.",
                    "High-quality material and stylish design.",
                    "Looks and feels premium. Very satisfied."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Car Accessories",
                "reviews" => [
                    "Super easy to install and very practical.",
                    "The product fits perfectly and looks amazing.",
                    "Definitely improved the interior look of my car.",
                    "High-quality and durable. Great value for money."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Car Electronics",
                "reviews" => [
                    "Works flawlessly and setup was quick.",
                    "Improved my driving experience significantly.",
                    "Crystal clear display and responsive controls.",
                    "A must-have for every tech-savvy driver."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Motorcycle Gear",
                "reviews" => [
                    "Comfortable and safe — just what I needed.",
                    "Perfect fit and stylish design.",
                    "Feels solid and protects well during rides.",
                    "Very impressed with the materials used."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Car Care",
                "reviews" => [
                    "Restored my car’s shine like brand new.",
                    "Easy to use and very effective.",
                    "Impressive results after the first use.",
                    "Smells nice and cleans thoroughly."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Tires & Wheels",
                "reviews" => [
                    "Great grip and smooth ride.",
                    "Reliable and long-lasting tires.",
                    "Very easy to balance and mount.",
                    "High-quality wheels that look fantastic."
                ]
            ],
            [
                "category" => "Sports & Outdoors",
                "subCategory" => "Fitness Equipment",
                "reviews" => [
                    "Compact, sturdy, and works like a charm.",
                    "Helps me stay on track with my fitness goals.",
                    "Fantastic build quality and smooth operation.",
                    "Worth every penny for home workouts."
                ]
            ],
            [
                "category" => "Sports & Outdoors",
                "subCategory" => "Outdoor Gear",
                "reviews" => [
                    "Weatherproof and extremely functional.",
                    "Perfect for weekend hikes and camping.",
                    "Lightweight yet very durable gear.",
                    "Smart design with plenty of storage."
                ]
            ],
            [
                "category" => "Sports & Outdoors",
                "subCategory" => "Cycling",
                "reviews" => [
                    "Smooth ride and top-notch build.",
                    "Feels premium and handles great.",
                    "Really impressed with the performance.",
                    "A perfect upgrade for my cycling needs."
                ]
            ],
            [
                "category" => "Sports & Outdoors",
                "subCategory" => "Water Sports",
                "reviews" => [
                    "Made my beach trip so much more fun.",
                    "Very buoyant and easy to control.",
                    "Solid quality and vibrant colors.",
                    "Lightweight and easy to carry around."
                ]
            ],
            [
                "category" => "Sports & Outdoors",
                "subCategory" => "Team Sports",
                "reviews" => [
                    "Great for group play and training.",
                    "Durable and great bounce.",
                    "Exactly what we needed for the team.",
                    "Kids loved using this for soccer practice."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Board Games",
                "reviews" => [
                    "So much fun for the entire family.",
                    "Engaging and educational — love it!",
                    "Great bonding activity with friends.",
                    "Beautiful design and durable pieces."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Educational Toys",
                "reviews" => [
                    "My child is learning while playing — perfect!",
                    "Bright colors and smart activities.",
                    "Keeps kids engaged for hours.",
                    "Very well designed for development."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Action Figures",
                "reviews" => [
                    "Incredible detail and great articulation.",
                    "Kids love reenacting their favorite scenes.",
                    "Feels sturdy and well-painted.",
                    "An instant favorite in our collection."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Puzzles",
                "reviews" => [
                    "Challenging and satisfying to complete.",
                    "Well-cut pieces and vibrant print.",
                    "Great brain workout and fun time.",
                    "Perfect family activity on weekends."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Remote Control Toys",
                "reviews" => [
                    "Fast, responsive, and so much fun.",
                    "Battery lasts long and easy to operate.",
                    "Impressive speed and control range.",
                    "Great for both kids and adults alike."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Fiction",
                "reviews" => [
                    "Couldn’t put it down until the last page.",
                    "Beautifully written and thought-provoking.",
                    "A thrilling story with amazing characters.",
                    "Totally immersive from start to finish."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Non-fiction",
                "reviews" => [
                    "Well-researched and engaging.",
                    "Learned a lot from this read.",
                    "Informative yet easy to digest.",
                    "Excellent for expanding your knowledge."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Children’s Books",
                "reviews" => [
                    "My kids adore the characters and story.",
                    "Lovely illustrations and fun storyline.",
                    "Perfect bedtime reading material.",
                    "Engaging and suitable for all ages."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Self-Help",
                "reviews" => [
                    "Truly inspired me to make changes.",
                    "Simple concepts with powerful impact.",
                    "Life-changing advice delivered clearly.",
                    "Great motivation and practical steps."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Cookbooks",
                "reviews" => [
                    "Delicious recipes and easy to follow.",
                    "Inspired me to cook more often.",
                    "Great tips and colorful photos.",
                    "Perfect gift for a food lover."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Vitamins & Supplements",
                "reviews" => [
                    "Boosted my energy levels noticeably.",
                    "Easy to swallow and no bad aftertaste.",
                    "Been using it daily and feeling great.",
                    "Excellent quality, highly recommended."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Personal Hygiene",
                "reviews" => [
                    "Feels fresh and gentle on the skin.",
                    "Cleans thoroughly and smells great.",
                    "Love how soft my skin feels after use.",
                    "Reliable brand with consistent quality."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Hair Care",
                "reviews" => [
                    "My hair feels softer and shinier.",
                    "Smells amazing and lathers well.",
                    "Great for daily use without buildup.",
                    "Helped reduce hair fall noticeably."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Skin Care",
                "reviews" => [
                    "Cleared up my skin in just a week.",
                    "Absorbs quickly and feels lightweight.",
                    "Very soothing and keeps my skin hydrated.",
                    "Noticed visible results in a short time."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Oral Care",
                "reviews" => [
                    "Teeth feel cleaner and fresher.",
                    "Improved my gum health significantly.",
                    "Whitening effect is clearly visible.",
                    "Leaves a fresh breath that lasts."
                ]
            ],
            [
                "category" => "Clothing & Accessories",
                "subCategory" => "Men’s Clothing",
                "reviews" => [
                    "Fits perfectly and feels premium.",
                    "Material is soft and breathable.",
                    "Stylish and comfortable all day.",
                    "Holds up well after multiple washes."
                ]
            ],
            [
                "category" => "Clothing & Accessories",
                "subCategory" => "Women’s Clothing",
                "reviews" => [
                    "Elegant design and super comfy.",
                    "True to size and flattering fit.",
                    "Great stitching and premium fabric.",
                    "Love the way it feels and flows."
                ]
            ],
            [
                "category" => "Clothing & Accessories",
                "subCategory" => "Kids’ Clothing",
                "reviews" => [
                    "Adorable and well-made outfits.",
                    "My kids love the colors and feel.",
                    "Durable and soft on the skin.",
                    "Perfect fit and machine washable."
                ]
            ],
            [
                "category" => "Clothing & Accessories",
                "subCategory" => "Shoes",
                "reviews" => [
                    "Comfortable for walking all day.",
                    "Stylish design and great arch support.",
                    "True to size and durable soles.",
                    "Perfect for both casual and formal wear."
                ]
            ],
            [
                "category" => "Clothing & Accessories",
                "subCategory" => "Watches",
                "reviews" => [
                    "Elegant look and accurate timekeeping.",
                    "Feels light yet looks luxurious.",
                    "Battery lasts long and strap is comfy.",
                    "Goes well with every outfit."
                ]
            ],
            [
                "category" => "Beauty",
                "subCategory" => "Makeup",
                "reviews" => [
                    "Blends easily and lasts all day.",
                    "Vibrant colors and smooth texture.",
                    "Great pigmentation and zero irritation.",
                    "Perfect for a flawless finish."
                ]
            ],
            [
                "category" => "Beauty",
                "subCategory" => "Nail Care",
                "reviews" => [
                    "Dries quickly and doesn’t chip easily.",
                    "Beautiful colors with salon-like shine.",
                    "Brush applies evenly and smoothly.",
                    "Strengthened my nails with regular use."
                ]
            ],
            [
                "category" => "Beauty",
                "subCategory" => "Fragrances",
                "reviews" => [
                    "Such a fresh and lasting scent.",
                    "Got compliments every time I wore it.",
                    "Perfect balance — not too strong.",
                    "Feels luxurious and sophisticated."
                ]
            ],
            [
                "category" => "Beauty",
                "subCategory" => "Hair Styling Tools",
                "reviews" => [
                    "Heats up fast and styles beautifully.",
                    "Tames my frizz in minutes.",
                    "Lightweight and very effective.",
                    "Salon results at home — love it!"
                ]
            ],
            [
                "category" => "Beauty",
                "subCategory" => "Skin Tools",
                "reviews" => [
                    "Really improved my skincare routine.",
                    "Easy to use and very effective.",
                    "Skin feels smoother and tighter.",
                    "High quality and worth the price."
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Dog Food",
                "reviews" => [
                    "My dog loves it and is healthier.",
                    "No upset stomach and shiny coat.",
                    "Good value and quality ingredients.",
                    "Highly recommended by my vet too."
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Cat Toys",
                "reviews" => [
                    "Keeps my cat entertained for hours.",
                    "Durable and safe to play with.",
                    "Colorful and interactive design.",
                    "My cat goes crazy for this toy!"
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Pet Grooming",
                "reviews" => [
                    "Makes grooming stress-free and easy.",
                    "Gentle yet effective brushes.",
                    "Great for shedding season.",
                    "Pets look clean and feel soft after use."
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Aquarium Supplies",
                "reviews" => [
                    "Clear water and happy fish!",
                    "Easy to maintain and set up.",
                    "Well-packaged and high quality.",
                    "Perfect for beginners and experts."
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Bird Supplies",
                "reviews" => [
                    "My parrot loves the toys and treats.",
                    "Very colorful and safe to use.",
                    "Great value for exotic bird owners.",
                    "Strong build and fun variety."
                ]
            ],
            [
                "category" => "Office Supplies",
                "subCategory" => "Stationery",
                "reviews" => [
                    "Great quality paper and pens.",
                    "Perfect for everyday office use.",
                    "Smooth writing and no ink smudging.",
                    "Affordable and long-lasting items."
                ]
            ],
            [
                "category" => "Office Supplies",
                "subCategory" => "Printers & Ink",
                "reviews" => [
                    "Crisp prints and fast output.",
                    "Easy to install and set up.",
                    "Ink lasts a long time.",
                    "Perfect for both home and work."
                ]
            ],
            [
                "category" => "Office Supplies",
                "subCategory" => "Desk Organizers",
                "reviews" => [
                    "Keeps everything neatly in place.",
                    "Solid build and stylish design.",
                    "Decluttered my workspace instantly.",
                    "Fits perfectly on my small desk."
                ]
            ],
            [
                "category" => "Office Supplies",
                "subCategory" => "Filing & Binders",
                "reviews" => [
                    "Organizing documents is now so easy.",
                    "Durable and well-designed.",
                    "Great for both school and office.",
                    "Clips are sturdy and hold papers well."
                ]
            ],
            [
                "category" => "Office Supplies",
                "subCategory" => "Whiteboards & Supplies",
                "reviews" => [
                    "Erases cleanly and writes smoothly.",
                    "Perfect size for my home office.",
                    "Markers are vivid and long-lasting.",
                    "Kids also love using it for learning."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Board Games",
                "reviews" => [
                    "Great family bonding time!",
                    "Easy to learn and super fun.",
                    "Classic game with hours of enjoyment.",
                    "High quality pieces and packaging."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Action Figures",
                "reviews" => [
                    "Very detailed and collectible.",
                    "My son absolutely loves them.",
                    "Durable and well-painted figures.",
                    "Great addition to any collection."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Educational Toys",
                "reviews" => [
                    "Engaging and very educational.",
                    "Helped my child improve skills fast.",
                    "Safe, colorful, and interactive.",
                    "Keeps them learning while playing."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Remote Control Toys",
                "reviews" => [
                    "Fast and easy to control.",
                    "Battery life is impressive.",
                    "Fun for kids and adults alike.",
                    "Great speed and sturdy build."
                ]
            ],
            [
                "category" => "Toys & Games",
                "subCategory" => "Outdoor Play Equipment",
                "reviews" => [
                    "Perfect for backyard fun.",
                    "Very safe and easy to assemble.",
                    "Keeps kids active and happy.",
                    "High-quality materials and stable design."
                ]
            ],
            [
                "category" => "Baby Products",
                "subCategory" => "Diapers",
                "reviews" => [
                    "No leaks and super absorbent.",
                    "Gentle on baby’s skin.",
                    "Great fit and easy to use.",
                    "Lasts through the night perfectly."
                ]
            ],
            [
                "category" => "Baby Products",
                "subCategory" => "Baby Food",
                "reviews" => [
                    "My baby loves the taste.",
                    "Nutritious and easy to serve.",
                    "Good ingredients and organic options.",
                    "Always fresh and well-packaged."
                ]
            ],
            [
                "category" => "Baby Products",
                "subCategory" => "Strollers",
                "reviews" => [
                    "Smooth ride and easy to fold.",
                    "Lightweight yet very durable.",
                    "Comfortable for baby and easy to push.",
                    "Great design with storage space."
                ]
            ],
            [
                "category" => "Baby Products",
                "subCategory" => "Car Seats",
                "reviews" => [
                    "Feels very secure and safe.",
                    "Easy to install and adjust.",
                    "My baby sleeps comfortably in it.",
                    "Solid construction and good cushioning."
                ]
            ],
            [
                "category" => "Baby Products",
                "subCategory" => "Baby Monitors",
                "reviews" => [
                    "Clear sound and video quality.",
                    "Gives peace of mind at night.",
                    "Good range and easy to operate.",
                    "Reliable and long battery life."
                ]
            ],
            [
                "category" => "Crafts & Sewing",
                "subCategory" => "Fabric",
                "reviews" => [
                    "Beautiful patterns and soft texture.",
                    "Perfect for my DIY projects.",
                    "High quality and easy to sew.",
                    "Color stays vibrant after wash."
                ]
            ],
            [
                "category" => "Crafts & Sewing",
                "subCategory" => "Sewing Machines",
                "reviews" => [
                    "Works smoothly and very efficient.",
                    "Great features for beginners.",
                    "Solid build and easy to maintain.",
                    "Perfect stitches every time."
                ]
            ],
            [
                "category" => "Crafts & Sewing",
                "subCategory" => "Knitting Supplies",
                "reviews" => [
                    "Yarns are soft and vibrant.",
                    "Needles feel sturdy and smooth.",
                    "Fun to work with and very relaxing.",
                    "Everything you need in one pack."
                ]
            ],
            [
                "category" => "Crafts & Sewing",
                "subCategory" => "Beading Supplies",
                "reviews" => [
                    "So many colors and great quality.",
                    "Perfect for bracelets and necklaces.",
                    "Easy to string and looks amazing.",
                    "Loved making crafts with these!"
                ]
            ],
            [
                "category" => "Crafts & Sewing",
                "subCategory" => "Painting Supplies",
                "reviews" => [
                    "Brushes are soft and precise.",
                    "Colors are bright and smooth.",
                    "Great for both beginners and pros.",
                    "Easy clean up and no mess."
                ]
            ],
            [
                "category" => "Health & Personal Care",
                "subCategory" => "Vitamins & Supplements",
                "reviews" => [
                    "I feel more energized after taking these daily.",
                    "Easy to swallow and no aftertaste.",
                    "Noticed a real difference in my immunity.",
                    "Highly recommended for overall wellness."
                ]
            ],
            [
                "category" => "Pet Supplies",
                "subCategory" => "Dog Food",
                "reviews" => [
                    "My dog absolutely loves this brand.",
                    "Great ingredients and no stomach issues.",
                    "Keeps his coat shiny and healthy.",
                    "Worth every penny for a happy pet."
                ]
            ],
            [
                "category" => "Automotive",
                "subCategory" => "Car Accessories",
                "reviews" => [
                    "Perfect fit for my vehicle.",
                    "Improved my driving comfort a lot.",
                    "Looks stylish and functions great.",
                    "Easy to install and durable quality."
                ]
            ],
            [
                "category" => "Books",
                "subCategory" => "Self-Help",
                "reviews" => [
                    "Truly inspiring and life-changing advice.",
                    "Easy to read with actionable insights.",
                    "Helped me build better daily habits.",
                    "A must-read for personal growth seekers."
                ]
            ],
            [
                "category" => "Fitness & Sports",
                "subCategory" => "Yoga Equipment",
                "reviews" => [
                    "Mat has great grip and cushioning.",
                    "Blocks and straps are very helpful.",
                    "High-quality and beginner-friendly gear.",
                    "Transformed my home workouts completely."
                ]
            ]
        ];

    }
}
