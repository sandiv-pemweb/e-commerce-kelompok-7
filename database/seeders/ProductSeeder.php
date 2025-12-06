<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Responsibilities:
     * 1. Create Product Categories
     * 2. Create Products
     * 3. Attach Product Images
     * 
     * Notes:
     * - Does NOT creating transactions or reviews (moved to TransactionSeeder)
     */
    public function run(): void
    {
        // 1. Get Stores
        $bookland = Store::where('name', 'BookLand Official')->firstOrFail();
        $gramedia = Store::where('name', 'Gramedia Digital')->firstOrFail();
        $periplus = Store::where('name', 'Periplus')->firstOrFail();
        $indie = Store::where('name', 'Indie Book Corner')->firstOrFail();

        // 2. Create Categories
        // BookLand Categories
        $catBusiness = ProductCategory::firstOrCreate(['store_id' => $bookland->id, 'name' => 'Business & Strategy', 'slug' => 'business-strategy']);
        $catSelfHelp = ProductCategory::firstOrCreate(['store_id' => $bookland->id, 'name' => 'Self Help', 'slug' => 'self-help']);
        
        // Gramedia Categories
        $catFiction = ProductCategory::firstOrCreate(['store_id' => $gramedia->id, 'name' => 'Fiction', 'slug' => 'fiction']);
        $catRomance = ProductCategory::firstOrCreate(['store_id' => $gramedia->id, 'name' => 'Romance', 'slug' => 'romance', 'parent_id' => $catFiction->id]);
        
        // Periplus Categories
        $catEnglish = ProductCategory::firstOrCreate(['store_id' => $periplus->id, 'name' => 'English Books', 'slug' => 'english-books']);
        $catBio = ProductCategory::firstOrCreate(['store_id' => $periplus->id, 'name' => 'Biography', 'slug' => 'biography']);

        // Indie Categories -> Now Moved to Gramedia (since Indie is unverified)
        $catSocial = ProductCategory::firstOrCreate(['store_id' => $gramedia->id, 'name' => 'Social & Politics', 'slug' => 'social-politics']);
        $catLit = ProductCategory::firstOrCreate(['store_id' => $gramedia->id, 'name' => 'Literature', 'slug' => 'literature']);

        // 3. Define Books Data
        $books = [
            // BookLand Official (Business & Self Help)
            [
                'store_id' => $bookland->id,
                'category_id' => $catBusiness->id,
                'name' => 'Think and Grow Rich',
                'author' => 'Napoleon Hill',
                'publisher' => 'The Ralston Society',
                'published_year' => 1937,
                'description' => 'Think and Grow Rich is a book written by Napoleon Hill in 1937 and promoted as a personal development and self-improvement book. He claimed to be inspired by a suggestion from business magnate and later-philanthropist Andrew Carnegie.',
                'price' => 150000,
                'discount_price' => 135000,
                'stock' => 50,
                'image' => 'https://images-cdn.ubuy.co.id/6784701671b4d304c0566c79-think-and-grow-rich-the-landmark.jpg'
            ],
            [
                'store_id' => $bookland->id,
                'category_id' => $catSelfHelp->id,
                'name' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Penguin Random House',
                'published_year' => 2018,
                'description' => 'No matter your goals, Atomic Habits offers a proven framework for improving--every day. James Clear, one of the world\'s leading experts on habit formation, reveals practical strategies that will teach you exactly how to form good habits, break bad ones, and master the tiny behaviors that lead to remarkable results.',
                'price' => 180000,
                'discount_price' => 162000,
                'stock' => 40,
                'image' => 'https://m.media-amazon.com/images/I/81kg51XRc1L.jpg'
            ],
            [
                'store_id' => $bookland->id,
                'category_id' => $catBusiness->id,
                'name' => 'The Psychology of Money',
                'author' => 'Morgan Housel',
                'publisher' => 'Harriman House',
                'published_year' => 2020,
                'description' => 'Doing well with money isn’t necessarily about what you know. It’s about how you behave. And behavior is hard to teach, even to really smart people. Money—investing, personal finance, and business decisions—is typically taught as a math-based field, where data and formulas tell us exactly what to do. But in the real world people don’t make financial decisions on a spreadsheet.',
                'price' => 125000,
                'discount_price' => 0,
                'stock' => 35,
                'image' => 'https://cdn.gramedia.com/uploads/items/psychology_of_money.jpg'
            ],

            // Gramedia Digital (Indonesian Fiction)
            [
                'store_id' => $gramedia->id,
                'category_id' => $catFiction->id,
                'name' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'published_year' => 2017,
                'description' => 'Laut Bercerita bertutur tentang kisah keluarga yang kehilangan, sekumpulan sahabat yang merasakan kekosongan di dada, sekelompok orang yang gemar menyiksa dan lancar berkhianat, sejumlah keluarga yang mencari kejelasan makam anaknya, dan tentang cinta yang tak akan luntur.',
                'price' => 115000,
                'discount_price' => 103500,
                'stock' => 60,
                'image' => 'https://imgv2-2-f.scribdassets.com/img/document/443499450/original/75e0895939/1?v=1'
            ],
            [
                'store_id' => $gramedia->id,
                'category_id' => $catFiction->id,
                'name' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'published_year' => 1980,
                'description' => 'Bumi Manusia adalah buku pertama dari Tetralogi Buru karya Pramoedya Ananta Toer yang pertama kali diterbitkan oleh Hasta Mitra pada tahun 1980. Buku ini ditulis Pramoedya ketika ia masih mendekam di pengasingan Pulau Buru.',
                'price' => 135000,
                'discount_price' => 0,
                'stock' => 25,
                'image' => 'https://m.media-amazon.com/images/S/compressed.photo.goodreads.com/books/1565658920i/1398034.jpg'
            ],
            [
                'store_id' => $gramedia->id,
                'category_id' => $catFiction->id,
                'name' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'published_year' => 2005,
                'description' => 'Laskar Pelangi adalah novel pertama karya Andrea Hirata yang diterbitkan oleh Bentang Pustaka pada tahun 2005. Novel ini bercerita tentang kehidupan 10 anak dari keluarga miskin yang bersekolah (SD dan SMP) di sebuah sekolah Muhammadiyah di Belitung yang penuh dengan keterbatasan.',
                'price' => 85000,
                'discount_price' => 76500,
                'stock' => 100,
                'image' => 'https://upload.wikimedia.org/wikipedia/id/thumb/8/8e/Laskar_pelangi_sampul.jpg/250px-Laskar_pelangi_sampul.jpg'
            ],
            [
                'store_id' => $gramedia->id,
                'category_id' => $catRomance->id,
                'name' => 'Hujan',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'published_year' => 2016,
                'description' => 'Novel Hujan karya Tere Liye menceritakan tentang persahabatan, cinta, perpisahan, melupakan, dan hujan. Latar belakang cerita ini adalah dunia di tahun 2042 hingga 2050, di mana teknologi sudah sangat maju.',
                'price' => 95000,
                'discount_price' => 85000,
                'stock' => 80,
                'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ938zLq7VNoof2AFRwnH8KAhFca6xoefqHUw&s'
            ],

            // Periplus (Imported)
            [
                'store_id' => $periplus->id,
                'category_id' => $catEnglish->id,
                'name' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'publisher' => 'Scholastic',
                'published_year' => 1997,
                'description' => 'Harry Potter has never been the star of a Quidditch team, scoring points while riding a broom far above the ground. He knows no spells, has never helped to hatch a dragon, and has never worn a cloak of invisibility.',
                'price' => 250000,
                'discount_price' => 200000,
                'stock' => 30,
                'image' => 'https://cdn.gramedia.com/uploads/items/9781408855652_Harry-Potter-1-Philosophers-Stone-20-Years-Sc.jpg'
            ],
            [
                'store_id' => $periplus->id,
                'category_id' => $catEnglish->id,
                'name' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Harper',
                'published_year' => 2011,
                'description' => 'From a renowned historian comes a groundbreaking narrative of humanity’s creation and evolution—a #1 international bestseller—that explores the ways in which biology and history have defined us and enhanced our understanding of what it means to be “human.”',
                'price' => 220000,
                'discount_price' => 0,
                'stock' => 20,
                'image' => 'https://cdn.gramedia.com/uploads/items/591701404_sapiens.jpg'
            ],
            [
                'store_id' => $periplus->id,
                'category_id' => $catBio->id,
                'name' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Simon & Schuster',
                'published_year' => 2011,
                'description' => 'Based on more than forty interviews with Jobs conducted over two years—as well as interviews with more than a hundred family members, friends, adversaries, competitors, and colleagues—Walter Isaacson has written a riveting story of the roller-coaster life and searingly intense personality of a creative entrepreneur.',
                'price' => 280000,
                'discount_price' => 250000,
                'stock' => 15,
                'image' => 'https://upload.wikimedia.org/wikipedia/id/thumb/e/e4/Steve_Jobs_by_Walter_Isaacson.jpg/250px-Steve_Jobs_by_Walter_Isaacson.jpg'
            ],

            // Indie Book Corner -> Moved to Gramedia
            [
                'store_id' => $gramedia->id,
                'category_id' => $catSocial->id,
                'name' => 'Madilog',
                'author' => 'Tan Malaka',
                'publisher' => 'Pusat Data Indikator',
                'published_year' => 1943,
                'description' => 'Madilog adalah karya magnum opus Tan Malaka. Buku ini berisi pemikiran Tan Malaka tentang Materialisme, Dialektika, dan Logika. Ditulis dalam kondisi pelarian dan persembunyian.',
                'price' => 110000,
                'discount_price' => 0,
                'stock' => 50,
                'image' => 'https://cdn.gramedia.com/uploads/products/ogimsi-ozs.jpg'
            ],
            [
                'store_id' => $gramedia->id,
                'category_id' => $catLit->id,
                'name' => 'Orang-Orang Bloomington',
                'author' => 'Budi Darma',
                'publisher' => 'Noura Books',
                'published_year' => 1980,
                'description' => 'Kumpulan cerpen karya Budi Darma yang berlatar di Bloomington, Indiana, Amerika Serikat. Buku ini memotret keterasingan manusia modern dengan gaya yang unik dan memikat.',
                'price' => 88000,
                'discount_price' => 79000,
                'stock' => 40,
                'image' => 'https://cdn.gramedia.com/uploads/items/9786023850211_orang-orang_bloomington.jpeg'
            ],
        ];

        // 4. Seed Products
        foreach ($books as $bookData) {
            $product = Product::create([
                'store_id' => $bookData['store_id'],
                'product_category_id' => $bookData['category_id'],
                'name' => $bookData['name'],
                'slug' => Str::slug($bookData['name']),
                'author' => $bookData['author'],
                'publisher' => $bookData['publisher'],
                'published_year' => $bookData['published_year'],
                'description' => $bookData['description'],
                'condition' => 'new',
                'price' => $bookData['price'],
                'discount_price' => $bookData['discount_price'],
                'weight' => 500,
                'stock' => $bookData['stock'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $bookData['image'],
            ]);
        }
    }
}
