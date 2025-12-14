<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', __DIR__ . '/../');
define('LANG', $_GET['lang'] ?? $_SESSION['lang'] ?? 'en');
$_SESSION['lang'] = LANG;

$host = 'localhost'; $db = 'meret_db'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $pdo = null;
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
}

if (!function_exists('getMockFile')) {
    function getMockFile($file) {
        return BASE_PATH . 'assets/data/' . $file . '.json';
    }
}



// Add to includes/translations.php or config.php


$translations = [
    'en' => [
        // General (existing + new)
        'dashboard_title' => 'Dashboard',
        'welcome_back' => 'Welcome back',
        'discover_fresh_produce' => 'Discover fresh, local produce and connect with farmers in your area. Shop smarter, support sustainably.',
        'start_shopping' => 'Start Shopping',
        'list_produce' => 'List Your Produce',
        'logout' => 'Logout',
        'search_placeholder' => 'Search for fresh produce, farmers, or prices...',
        'cart' => 'Cart',
        'home' => 'Home',
        'back' => 'Back',
        'market_price' => 'Market Price',
        'reviews' => 'Reviews',
        'post_produce' => 'Post Produce',
        'approvals' => 'Approvals',
        'inventory' => 'Inventory',
        'agent' => 'Agent',
        'analytics' => 'Analytics',
        'transport_simulated' => 'Transport request simulated! Redirecting...',
        'inventory_initiated' => 'Inventory check initiated! Loading data...',
        'dashboard' => 'Dashboard',
        'welcome' => 'Welcome',
        'browse_produces' => 'Browse Produces',
        'market_prices' => 'Market Prices',
        'my_orders' => 'My Orders',


        // Quick Actions (existing)
        'quick_actions' => 'Quick Actions',
        'explore_features' => 'Explore essential features tailored to your role. Dive in and get started.',

        // Farmer (existing + new where applicable)
        'post_produce' => 'Post Produce', // Already added
        'add_harvest_produce' => 'Add your harvested produce for sale in the marketplace for buyers to see. Reach more customers effortlessly.',
        'new_listing' => 'New Listing',
        'post_now' => 'Post Now',
        'request_transport' => 'Request Transport',
        'arrange_pickup' => 'Arrange pickup of your produce to collection centers or buyers quickly. Streamline your logistics.',
        'quick_request' => 'Quick Request',
        'order_tracking' => 'Order Tracking',
        'monitor_orders' => 'Monitor the progress of your orders from placement to delivery. Stay updated in real-time.',
        'live_tracking' => 'Live Tracking',
        'payment_tracking' => 'Payment Tracking',
        'check_payments' => 'Check the status of payments for delivered produce. Ensure timely settlements with ease.',
        'secure' => 'Secure',
        'submit_price' => 'Submit Local Market Price',
        'share_prices' => 'Share the prices you observe in local markets to help calculate averages. Contribute to fair pricing.',
        'community_driven' => 'Community Driven',
        'view_prices' => 'View City Average Prices',
        'see_averages' => 'See crowdsourced average market prices across major cities. Make informed selling decisions.',
        'trends' => 'Trends',

        // Consumer (existing + new)
        'browse_produce' => 'Browse Produce',
        'view_explore_produce' => 'View and explore produce available from farmers near you. Fresh picks at your fingertips.',
        'fresh_daily' => 'Fresh Daily',
        'browse_now' => 'Browse Now',
        'submit_market_price' => 'Submit Market Price',
        'submit_observed_prices' => 'Submit observed prices to contribute to city-wide average price data. Help build transparent markets.',
        'support_local' => 'Support Local',
        'my_reviews' => 'My Reviews',
        'leave_feedback' => 'Leave feedback for farmers and view your past reviews. Share your experience and rate quality.',
        'rate_review' => 'Rate & Review',
        'view_price_trends' => 'View Price Trends',
        'check_trends_averages' => 'Check trends and average prices for crops across different cities. Shop smarter with data.',
        'insights' => 'Insights',
        'view_trends' => 'View Trends',

        // Agent (existing + new)
        'approve_listings' => 'Approve Listings',
        'verify_approve_listings' => 'Verify and approve farmer produce listings for quality and accuracy. Maintain marketplace integrity.',
        'pending_approvals' => 'Pending Approvals',
        'manage_collection_center' => 'Manage Collection Center',
        'monitor_inventory_logistics' => 'Monitor inventory and coordinate logistics at collection centers. Optimize operations seamlessly.',
        'check_inventory' => 'Check Inventory',
        'approvals' => 'Approvals', // New
        'inventory' => 'Inventory', // New

        // Admin (existing + new)
        'approve_agents' => 'Approve Agents',
        'review_approve_agents' => 'Review and approve new agent registrations with proper credentials. Ensure trusted partnerships.',
        'new_requests' => 'New Requests',
        'analytics' => 'Analytics', // New
        'view_system_analytics' => 'View system-wide analytics, trends, and reports for decision making. Drive platform growth.',
        'real_time' => 'Real-Time',
        'support' => 'Support',
        'handle_user_queries' => 'Handle user queries, disputes, and provide assistance efficiently. Keep the community thriving.',
        'messages_new' => 'Messages: 5 New',
        'agent' => 'Agent', // New

        // Featured Products (existing)
        'featured_fresh_produce' => 'Featured Fresh Produce',
        'add_to_cart' => 'Add to Cart',

        // Other (existing)
        'transport_simulated' => 'Transport request simulated! Redirecting...',
        'inventory_initiated' => 'Inventory check initiated! Loading data...'
    ],
    'am' => [
        // General
        'dashboard_title' => 'ዳሽቦርድ',
        'welcome_back' => 'እንኳን ተመልስተው መገናኛ',
        'discover_fresh_produce' => 'በአካባቢዎ ያሉ አቅራቢት እና አማራጮች ያገናኙ። በብልህነት ይግዙ እና በውስጣዊ መንገድ ይደግፉ።',
        'start_shopping' => 'ይጀምሩ ይግዙ',
        'list_produce' => 'አማራጭዎን ያክሉ',
        'logout' => 'ይወጡ',
        'search_placeholder' => 'ትኩስ አማራጮች፣ አማራጮች ወይም ዋጋዎች ይፈልጉ...',
        'cart' => 'ጋሪ',
        'home' => 'መነሻ',
        'back' => 'ይመለሱ',
        'market_price' => 'የገበያ ዋጋ',
        'reviews' => 'ግምቶች',
        'post_produce' => 'አማራጭ ያስገቡ',
        'approvals' => 'ማግኘቶች',
        'inventory' => 'ግቢ',
        'agent' => 'ወኪል',
        'analytics' => 'በተግባር የተገነባ',
        'transport_simulated' => 'የትራንስፖርት ጥያቄ ተሰርቷል! እየተመለሰ ነው...',
        'inventory_initiated' => 'የግቢ ምርመራ ተጀምሯል! ውሂብ እየተጫነ ነው...',
        'dashboard' => 'ዳሽቦርድ',
        'welcome' => 'እንኳን ደህና መጡ',
        'browse_produces' => 'አማራጮችን ይመልከቱ',
        'market_prices' => 'የገበያ ዋጋዎች',
        'my_orders' => 'የእኔ ትዕዛዞች',


        // Quick Actions
        'quick_actions' => 'ፈጣን እርምጃዎች',
        'explore_features' => 'በይቲዎ ሚዛን የተዘጋጁ ቁልፍ ባህሪያትን ይጠቀሙ። በአንድ ተንቀስ ይጀምሩ።',

        // Farmer
        'add_harvest_produce' => 'በገበያው ውስጥ ለሽያጭ የተሰበሰበው አማራጭዎን ያክሉ። በቀላሉ በተጨማሪ ደንበኞች ይደርሱ።',
        'new_listing' => 'አዲስ ዝርዝር',
        'post_now' => 'አሁን ያስገቡ',
        'request_transport' => 'ትራንስፖርት ይጠይቁ',
        'arrange_pickup' => 'አማራጭዎን ወደ ስብ ማዕከሎች ወይም ሽያጮች በፍጥነት ያደራጁ። ሎጂስቲክስዎን ያስቀምጡ።',
        'quick_request' => 'ፈጣን ጥያቄ',
        'order_tracking' => 'ትዕዛዝ ትራክንግ',
        'monitor_orders' => 'ትዕዛዞችዎን ከተቀመጡ እስከ የሚያመጣ ድረስ ያከታተሉ። በህጋዊ ጊዜ ይታወቁ።',
        'live_tracking' => 'ቅጥታ ትራክንግ',
        'payment_tracking' => 'ክፍያ ትራክንግ',
        'check_payments' => 'ለተላከ አማራጭ ክፍያዎች ሁኔታን ያረጋግጡ። በቀላሉ በደንብ የገዢ ማድረግ ያስታውሱ።',
        'secure' => 'ደህንነቱ የተጠበቀ',
        'submit_price' => 'የአካባቢ ገበያ ዋጋ ይላክ',
        'share_prices' => 'በአካባቢዎ ገበያዎች ውስጥ የምታዩትን ዋጋዎች ይላኩ በአማካይ እንዲሆኑ ይረዱ። ለፍትሃዊ ዋጋ ይረዱ።',
        'community_driven' => 'በማህበረሰብ የተመራ',
        'view_prices' => 'የከተማ አማካይ ዋጋዎችን ይመልከቱ',
        'see_averages' => 'በትላልቅ ከተሞች በኩል የተገበረ አማካይ ገበያ ዋጋዎችን ይመልከቱ። የሽያጭ ውሳኔዎችን ያውቃሉ።',
        'trends' => 'አዝማሚያዎች',
        'browse_produce' => 'አማራጭ ይመልከቱ',
        'submit_market_price' => 'የገበያ ዋጋ ይላኩ',
        'my_reviews' => 'የእኔ ግምቶች',
        'view_price_trends' => 'የዋጋ አዝማሚያዎችን ይመልከቱ',
        'featured_fresh_produce' => 'የተለያዩ ትኩስ አማራጮች',
        'add_to_cart' => 'ወደ ጋሪ ያክሉ',
        'teff' => 'ጤፍ',
        'tomato' => 'ቲማቲም',
        'banana' => 'ሙዝ',
        'order_now' => 'አሁን ይዘዙ',
        'orders' => 'ትዕዛዞች',
        'prices' => 'ዋጋዎች',
        'potato' => 'ድንች',
        'onion' => 'ሽንኩርት',
        'carrot' => 'ካሮት',

        // Consumer
        'browse_produces' => 'አማራጮችን ይመልከቱ',
        'view_explore_produce' => 'በአካባቢዎ ላሉ አማራጮች የሚገኙ አማራጮችን ይመልከቱ እና ይጠቀሙ። በአንጻራችዎ ትኩስ ምርጫዎች።',
        'fresh_daily' => 'በየቀኑ ትኩስ',
        'browse_now' => 'አሁን ይጎብኙ',
        'submit_observed_prices' => 'የተመለከተውን ዋጋዎች ይላኩ በከተማ ሰፊ አማካይ ዋጋ ውሂብ ለመስጠት ይረዱ። ቸልተኛ ገበያዎችን ይገነቡ።',
        'support_local' => 'አካባቢያዊን ይደግፉ',
        'leave_feedback' => 'ለአማራጮች ግመት ይሉ እና ያላቸውን ያለፉ ግምቶች ይመልከቱ። ተሞክሮዎን ይላኩ እና ጥራት ያገኙ።',
        'rate_review' => 'ደረጃ ያድርጉ & ግምት ይሉ',
        'check_trends_averages' => 'ለተለያዩ ከተሞች በኩል ለአማራጮች አዝማሚያዎች እና አማካይ ዋጋዎችን ይፈትሹ። በብልህነት ይግዙ።',
        'insights' => 'ተግባራት',
        'view_trends' => 'አዝማሚያዎችን ይመልከቱ',

        // Agent
        'verify_approve_listings' => 'የአማራጭ አማራጮች ዝርዝሮችን ለጥራት እና ትክክለኛነት ያረጋግጡ እና ያገናኙ። የገበያ ማንቂያን ይጠብቁ።',
        'pending_approvals' => 'የተጠበቀ ማግኘቶች',
        'monitor_inventory_logistics' => 'በስብ ማዕከሎች ውስጥ ግቢ እና ሎጂስቲክስ ቅንጅት ያከታተሉ። በቀላሉ ተግባራትን ያጠናከሩ።',
        'check_inventory' => 'ግቢ ይፈትሹ',
        'approvals' => 'ማግኘቶች',
        'inventory' => 'ዝርዝር',
        'agent' => 'ወኪል',
        'analytics' => 'በተግባር የተገነባ',
        'approve_listings' => 'ዝርዝሮችን ያገናኙ',
        'manage_collection_center' => 'የስብ ማዕከል ያስተዳዱ',
        'approve_agents' => 'ወኪሎችን ያገናኙ',
        // Admin
        'review_approve_agents' => 'አዲስ የወኪል መመዝገቢያዎችን በተገለጹ አስተማማኝያዎች ያረጋግጡ እና ያገናኙ። የተጠበቀ ትብብርዎችን ያረጋግጡ።',
        'new_requests' => 'አዲስ ጥያቄዎች',
        'view_system_analytics' => 'ስርዓተ ማህበረሰብ በኩል በተግባር የተገነባ፣ አዝማሚያዎች እና ሪፖርቶችን ለውሳኔ አድርጎት ይመልከቱ። የፕላትፎርም እድገትን ያነቃቃሩ።',
        'real_time' => 'በዘላ ጊዜ',
        'handle_user_queries' => 'የተጠቃሚ ጥያቄዎችን፣ ክርክሮችን እና በቀላሉ ረዳት ይሰጣሉ። ማህበረሰቡን የሚያነቃቃር ይጠብቁ።',
        'messages_new' => 'መልእክቶች: 5 አዲስ',
        'support' => 'ድጋፍ',
        "analytics" => "በተግባር የተገነባ",
        'agents' => 'ወኪል',
        'sms' => 'ኤስኤምኤስ',
        'apprive_agents' => 'ወኪሎችን ያገናኙ',
        'view_analytics' => 'በተግባር የተገነባ ይመልከቱ',

        // Featured Products
        'add_to_cart' => 'ወደ ጋሪ ያክሉ',

        // Other
        'transport_simulated' => 'የትራንስፖርት ጥያቄ ተሰማርቷል! ይመልሳሉ...',
        'inventory_initiated' => 'የግቢ ፍተሻ ተጀመረ! ውሂብ እየተጫነ ነው...'
    ],
    'om' => [
        // General
        'dashboard_title' => 'Dashboard',
        'welcome_back' => 'Galataa garaa deemu',
        'discover_fresh_produce' => 'Aamroonniin taa\'aan qabeenya garaa fi barumsaan dabalatee. Gaarii fi dhiiga qabuu.',
        'start_shopping' => 'Gaarii Deemu',
        'list_produce' => 'Aamroottii Keessatti Qabuu',
        'logout' => 'Galagala',
        'search_placeholder' => 'Aamroonniin taa\'aan, barumsaan, ykn qabeenyaaniif Gaafadhu...',
        'cart' => 'Gaarii',
        'home' => 'Garaa',
        'back' => 'Duraa',
        'market_price' => 'Qabeenya Gaarii',
        'reviews' => 'Bal\'inaan',
        'post_produce' => 'Aamroottii Qophaa\'e',
        'approvals' => 'Qophaa\'ee',
        'inventory' => 'Gaarii',
        'agent' => 'Barumsa',
        'analytics' => 'Ilaalama',
        'transport_simulated' => 'Gaaffii transport qophaa\'e! Deemu...',
        'inventory_initiated' => 'Gaarii ilaaluu qophaa\'e! Waliin deemu...',
        'dashboard' => 'Dashboard',
        'welcome' => 'Baga Nagaan Dhuftan',
        'browse_produces' => 'Aamroonniin Goobuu',
        'market_prices' => 'Qabeenya Gaarii',
        'my_orders' => 'Aamroottii Koo',


        // Quick Actions
        'quick_actions' => 'Imaammata Gaaffii',
        'explore_features' => 'Imaammataan keessatti jiraachuu fi deemu.',

        // Farmer
        'add_harvest_produce' => 'Aamroottii keessatti qophaa\'e. Barumsaan deemu.',
        'new_listing' => 'Qabiyyee Qophaa\'e',
        'post_now' => 'Aadaa Qophaa\'e',
        'request_transport' => 'Gaaffii Transport Qophaa\'e',
        'arrange_pickup' => 'Aamroottii keessatti qophaa\'e. Gaarii fi dhiiga qabuu.',
        'quick_request' => 'Gaaffii Gaafataa',
        'order_tracking' => 'Qophaa\'otaa Trackuu',
        'monitor_orders' => 'Qophaa\'otaa keessatti jiraachuu.',
        'live_tracking' => 'Live Trackuu',
        'payment_tracking' => 'Qabeenya Trackuu',
        'check_payments' => 'Qabeenya keessatti jiraachuu.',
        'secure' => 'Qajeelfamaa',
        'submit_price' => 'Qabeenya Ilaallee',
        'share_prices' => 'Qabeenya keessatti ilaaluu.',
        'community_driven' => 'Mootummaa Qabuu',
        'view_prices' => 'Qabeenya Ilaallee',
        'see_averages' => 'Qabeenya keessatti ilaaluu.',
        'trends' => 'Aadaa',
        'order_now' => 'Aadaa Deemu',
        'orders' => 'Aamroottii',
        'prices' => 'Qabeenya',


        // Consumer
        'view_explore_produce' => 'Aamroonniin keessatti goobuu.',
        'fresh_daily' => 'Aadaa Taa\'aan',
        'browse_now' => 'Aadaa Goobuu',
        'submit_observed_prices' => 'Qabeenya ilaaluu.',
        'support_local' => 'Gaarii Qabuu',
        'leave_feedback' => 'Bal\'inaan qabuu.',
        'rate_review' => 'Bal\'inaan Qabuu',
        'check_trends_averages' => 'Qabeenya aadaa ilaaluu.',
        'insights' => 'Ilaalama',
        'view_trends' => 'Aadaa Ilaallee',
        'browse_produce' => 'Aamroonniin Goobuu',
        'submit_market_price' => 'Qabeenya Ilaallee',
        'my_reviews' => 'Bal\'inaan Koo',
        'view_price_trends' => 'Qabeenya Aadaa Ilaallee',
        'featured_fresh_produce' => 'Aamroonniin Taa\'aan Qophaa\'e',
        'teff'=>'Teff',
        'banana'=>'Muzii',
        'carrot'=>'Kaarotii',
        'tomato'=>'Timaatimii',
        'potato'=>'Baqilaa',
        'onion'=>'Qullubbii',

        // Agent
        'verify_approve_listings' => 'Qabiyyee qophaa\'e.',
        'pending_approvals' => 'Qophaa\'ee Deemu',
        'monitor_inventory_logistics' => 'Gaarii fi dhiiga qabuu.',
        'check_inventory' => 'Gaarii Ilaallee',
        'approve_listings' => 'Qabiyyee Qophaa\'e',
        'manage_collection_center' => 'Keenyaa Ilaallee',

        // Admin
        'review_approve_agents' => 'Barumsaan qophaa\'e.',
        'new_requests' => 'Gaaffii Qophaa\'e',
        'view_system_analytics' => 'Analytics ilaaluu.',
        'real_time' => 'Live',
        'handle_user_queries' => 'Gaaffii dhiiga qabuu.',
        'messages_new' => 'Malleekoo 5 Qophaa\'e',

        // Featured Products
        'add_to_cart' => 'Gaarii Keessatti Qabuu',

        // Other
        'transport_simulated' => 'Gaaffii transport qophaa\'e! Deemu...',
        'inventory_initiated' => 'Gaarii ilaaluu qophaa\'e! Waliin deemu...'
    ]
];

// Function to get translated text (as before)
function t($key, $lang = null) {
    global $translations;
    $currentLang = $lang ?: ($_SESSION['lang'] ?? 'en');
    return $translations[$currentLang][$key] ?? $key;
}
