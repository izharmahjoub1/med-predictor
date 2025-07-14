# Med-Predictor

A comprehensive medical prediction and healthcare management system built with Laravel.

## Features

- **User Management**: Role-based authentication system with different user types
- **Player Registration**: Complete player management system with health records
- **Medical Predictions**: AI-powered medical prediction system
- **Health Records**: Comprehensive health record management
- **Club Management**: Football club and association management
- **FIFA Integration**: Connect with FIFA systems for player data
- **Team Management**: Lineup and team management features

## Technology Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **Real-time**: Livewire components

## Installation

1. Clone the repository:
```bash
git clone https://github.com/izharmahjoub1/med-predictor.git
cd med-predictor
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file

7. Run migrations:
```bash
php artisan migrate
```

8. Seed the database:
```bash
php artisan db:seed
```

9. Start the development server:
```bash
php artisan serve
```

## User Roles

- **Association Admin**: Manage associations and clubs
- **Club Admin**: Manage club players and teams
- **Healthcare Provider**: Access health records and predictions
- **Player**: View personal health records

## Modules

- **Player Registration**: Bulk import and individual player registration
- **Health Records**: Comprehensive health data management
- **Medical Predictions**: AI-powered health predictions
- **User Management**: Role-based user administration
- **Club Management**: Team and lineup management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support, please open an issue on GitHub or contact the development team.
