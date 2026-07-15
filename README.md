Yumz is a comprehensive, rolebased culinary marketplace platform that connects customers, professional cooks (cookers), and administrators. Built on top of the Laravel framework, the platform offers features like digital recipe purchasing, hiring personal chef services, realtime/interactive messaging, wallet & currency systems with exchange rate conversion, notifications, and an administrative moderation panel.

Key Features :

RoleBased Dashboards & Workflows :

1. Customer (Client)
    Browse & Follow: Find professional chefs, view their culinary profiles, follow their updates, and view their recipes/cooking services.
    Recipe Market: Purchase digital recipes from chefs, rate/review recipes, and view instructions for purchased recipes.
    Service Booking: Book professional cooking services, input preferences/details, and track service order status in real time.
    Wallet Management: Top up wallet balance and view transaction history. Supports Singapore Dollar (SGD), Malaysian Ringgit (MYR), and Indonesian Rupiah (IDR) with automated currency conversion.
    Interactive Chat: Chat directly with cookers, edit/delete sent messages, reply to messages, and receive chat notifications.

2. Cooker (Chef)
    Chef Profile: Maintain a public profile showcasing bios, rating stats, recipes, and services.
    Recipe Management: Create, edit, publish, and delete digital recipes (pricing, category, halal status, and covers).
    Cooking Service Management: Offer, edit, toggle availability, and delete cooking services.
    Order Dispatching: Receive bookings, track progress, and update the status of customer orders (e.g., pending, inprogress, completed).

3. Administrator
    User Moderation: View all users, edit details, and toggle account suspension.
    Content Control: Manage/moderate recipes and cooking services (publish toggle, visibility, and deletion).
    Transaction Ledger: Track all wallet topups, recipe purchases, and service order payments platformwide.
    System Activity Logs: Audit logs of actions performed by administrators, cookers, and users for security.



 Technology Stack :

 Backend Framework: [Laravel 11](https://laravel.com) (PHP 8.2+)
 Database: PostgreSQL (image: postgres:16)
 KeyValue Store / Cache: Redis (image: redis:alpine)
 WebSockets / Realtime: Laravel Reverb (integrated for messaging & status updates)
 Asset Bundler: Vite
 Frontend Layer: Blade Templates, Vanilla CSS, JavaScript / AJAX updates
 Testing Suite: PHPUnit / Laravel Feature & Unit tests



Testing Credentials :

For testing purposes, the application includes preseeded roles with default passwords.

| Role | Email | Password | Details |
| : | : | : | : |
| Admin | erwan@gmail.com | 12345678 | full administrative controls |
| Customer | customer@gmail.com | 12345678 | client account with initialized wallet balance |
| Cooker | cooker@gmail.com | 12345678 | cooker account (Chef Erwan) |
| Cooker | cooker1@gmail.com | 12345678 | cooker account (chef Erwan) |

You can also create new customer or cooker accounts directly using the /register interface.



Setup & Installation Instructions :

Github Repo : https://github.com/Erwann777/YUMZ-ChefWeb.git

You can run this application locally either via Docker (Laravel Sail) or using a Manual Environment Setup.

 Option A: Running with Docker (Recommended)

This project has Laravel Sail preconfigured. Ensure you have [Docker Desktop](https://www.docker.com/products/dockerdesktop/) installed and running on your machine.

1. Clone the Repository
   bash
   git clone <repositoryurl>
   cd Yumz
   

2. Configure Environment Variables
   Copy the example environment file:
   bash
   cp .env.example .env
   

3. Start Containers
   Start the application containers (Laravel, PostgreSQL, Redis) in the background:
   bash
    On macOS / Linux / WSL
   ./vendor/bin/sail up d

    On Windows (PowerShell)
   docker compose up d
   

4. Install Dependencies & Compile Assets
   Install Composer and Node modules inside the container, and run Vite compilation:
   bash
    On macOS / Linux / WSL
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build

    On Windows (PowerShell)
   docker compose exec laravel.test composer install
   docker compose exec laravel.test npm install
   docker compose exec laravel.test npm run build
   

5. Generate App Key
   bash
    On macOS / Linux / WSL
   ./vendor/bin/sail artisan key:generate

    On Windows (PowerShell)
   docker compose exec laravel.test php artisan key:generate
   

6. Run Database Migrations & Seeds
   Run the database migrations and seed default credentials:
   bash
    On macOS / Linux / WSL
   ./vendor/bin/sail artisan migrate seed

    On Windows (PowerShell)
   docker compose exec laravel.test php artisan migrate seed
   

7. Link Storage Directory
   Ensure user uploads (profile pictures, recipe/service images) are accessible:
   bash
    On macOS / Linux / WSL
   ./vendor/bin/sail artisan storage:link

    On Windows (PowerShell)
   docker compose exec laravel.test php artisan storage:link
   

8. Access the App
   Open your browser and navigate to http://localhost.



 Option B: Manual Environment Setup (Local PHP)

If you prefer to run the application directly on your host machine, make sure you have PHP 8.2+, PostgreSQL, Redis, and Node.js 20+ installed locally.

1. Install PHP Dependencies
   bash
   composer install
   

2. Install Frontend Dependencies
   bash
   npm install
   

3. Configure Environment
    Copy .env.example to .env.
    Update DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD matching your local PostgreSQL configuration.
    Configure local Redis host if enabled.

4. Setup Database & App Keys
   bash
   php artisan key:generate
   php artisan migrate seed
   php artisan storage:link
   

5. Build Assets or Run Vite Dev Server
   bash
    Build production assets
   npm run build

    Or run Vite live reloading development server
   npm run dev
   

6. Serve the Application
   bash
   php artisan serve
   
   Open http://127.0.0.1:8000 to view the application.



Running Automated Tests :

The application includes a comprehensive test suite of both Unit Tests and Feature Tests powered by [PHPUnit](https://phpunit.de/).

Test Configuration & Environment :
The testing environment is configured in [phpunit.xml](file:///d:/Yumz/phpunit.xml). To ensure tests run quickly and isolation is maintained, the test suite uses an inmemory SQLite database:
 Database Connection: sqlite
 Database Name: :memory:

Executing the Tests :
You can run the entire suite using either Docker (Laravel Sail) or your local environment:

bash
 Using Docker / Sail
./vendor/bin/sail artisan test

 Using local PHP
php artisan test


Alternatively, to run tests with PHPUnit directly:
bash
vendor/bin/phpunit


Test Suite Structure :
The test files are organized as follows:

Unit Tests (tests/Unit)
These tests verify core model behaviors, custom attributes, helper logic, and individual services:
 Models:
   [UserModelTest.php](file:///d:/Yumz/tests/Unit/Models/UserModelTest.php): Validates user roles (isAdmin, isCooker, isCustomer), currency codes, and country mappings.
   [RecipeModelTest.php](file:///d:/Yumz/tests/Unit/Models/RecipeModelTest.php): Asserts published recipe scopes and purchase checking.
   [CookingServiceModelTest.php](file:///d:/Yumz/tests/Unit/Models/CookingServiceModelTest.php): Tests availability scopes and currency formatting.
   [ServiceOrderModelTest.php](file:///d:/Yumz/tests/Unit/Models/ServiceOrderModelTest.php): Verifies order states (pending, completed, etc.) and relations.
   [WalletTransactionModelTest.php](file:///d:/Yumz/tests/Unit/Models/WalletTransactionModelTest.php): Tests credit/debit transaction logs, references, and currency conversions.
   [ActivityLogModelTest.php](file:///d:/Yumz/tests/Unit/Models/ActivityLogModelTest.php): Ensures audit trail logging and icon representations.
 Services:
   [CurrencyServiceTest.php](file:///d:/Yumz/tests/Unit/Services/CurrencyServiceTest.php): Validates currency conversions (SGD, MYR, IDR), exchange rates, and decimal formats.

 Feature Tests (tests/Feature)
These tests cover multistep HTTP requests, routing, authorization, and validation:
 [AdminDashboardControllerTest.php](file:///d:/Yumz/tests/Feature/Admin/AdminDashboardControllerTest.php): Tests admin controls, user moderation, suspending/deleting accounts, and toggling content.
 [CookingServiceControllerTest.php](file:///d:/Yumz/tests/Feature/CookingService/CookingServiceControllerTest.php): Checks cooker service creation, editing, update, deletion, and validation rules.
 [RecipeControllerTest.php](file:///d:/Yumz/tests/Feature/Recipe/RecipeControllerTest.php): Covers cooker recipe management flows and permissions.
 [WalletControllerTest.php](file:///d:/Yumz/tests/Feature/Wallet/WalletControllerTest.php): Asserts wallet topups, limits, transaction logging, and currencyspecific initial balance.
 [ExampleTest.php](file:///d:/Yumz/tests/Feature/ExampleTest.php): Basic login and registration workflow tests.



Latest Test Run Results :
All tests in the suite run and pass successfully. Below is the summary of the latest execution:

| Metric | Status / Value |
| : | : |
| Execution Status | PASS |
| Total Tests Run | 177 |
| Total Assertions | 253 |
| Execution Duration | 8.65s |


   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Unit\Models\ActivityLogModelTest
   PASS  Tests\Unit\Models\CookingServiceModelTest
   PASS  Tests\Unit\Models\RecipeModelTest
   PASS  Tests\Unit\Models\ServiceOrderModelTest
   PASS  Tests\Unit\Models\UserModelTest
   PASS  Tests\Unit\Models\WalletTransactionModelTest
   PASS  Tests\Unit\Services\CurrencyServiceTest
   PASS  Tests\Feature\Admin\AdminDashboardControllerTest
   PASS  Tests\Feature\CookingService\CookingServiceControllerTest
   PASS  Tests\Feature\ExampleTest
   PASS  Tests\Feature\Recipe\RecipeControllerTest
   PASS  Tests\Feature\Wallet\WalletControllerTest

  Tests:    177 passed (253 assertions)
  Duration: 8.65s




Key File Map :

Routes: [web.php](file:///d:/Yumz/routes/web.php)
Database Seeder: [DatabaseSeeder.php](file:///d:/Yumz/database/seeders/DatabaseSeeder.php)
User Model: [User.php](file:///d:/Yumz/app/Models/User.php)


