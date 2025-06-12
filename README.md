# CarRent Application

A Laravel-based car rental system with user authentication, car management, rental processing, and favorites functionality.

## Current Features

### Authentication & User Management
- User registration and login
- OTP verification system
- User profile management
- Role-based access control
- Profile image upload
- User history tracking

### Car Management
- Car listing with details
- Car categories
- Car availability tracking
- Car images
- Car specifications (model, color, year, price, etc.)
- Transmission types
- Fuel types
- Seat capacity

### Rental System
- Car rental booking
- Rental status tracking
- Payment processing
- Rental history
- Rental cancellation
- Payment confirmation
- Discount code system

### Favorites System
- Add/remove cars to favorites
- View favorite cars
- Favorites count tracking
- Real-time favorites updates

### Discount System
- Discount code management
- Percentage-based discounts
- Time-limited discounts
- Discount validation

## Technical Stack

### Backend
- Laravel 12.x
- MySQL Database
- Laravel Sanctum for API authentication
- Laravel Cashier for payments

### Frontend
- Blade templating
- jQuery for AJAX and interactivity
- TailwindCSS for styling
- Alpine.js for simple interactivity

### Models
- User (with roles)
- Car
- Category
- Rental
- UserHistory
- UserFavorite
- Discount
- Role

## Project Structure

### Routes
- Web routes for user interface
- API routes for AJAX calls
- Protected routes for authenticated users
- Public routes for car browsing

### Controllers
- AuthController (authentication, profile, favorites)
- CarController (car management)
- RentalController (rental processing)
- DiscountController (discount management)
- UserHistoryController (rental history)

### Views
- Authentication views (login, register)
- Car views (listing, details)
- Rental views (booking, history)
- Profile views
- Favorites views

## Getting Started

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env`
4. Generate app key:
   ```bash
   php artisan key:generate
   ```
5. Configure database in `.env`
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Seed the database:
   ```bash
   php artisan db:seed
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```

## Development Guidelines

- Follow PSR-12 coding standards
- Use Laravel's built-in features
- Implement proper error handling
- Use jQuery for AJAX operations
- Follow Laravel 12 conventions
- Use proper database indexing
- Implement proper validation
- Use Laravel's authentication system
- Follow RESTful API practices
- Use proper security measures

## Security Features

- CSRF protection
- Rate limiting on login
- Password hashing
- OTP verification
- Role-based access
- Input validation
- SQL injection prevention
- XSS protection

## License

This project is licensed under the MIT License.
