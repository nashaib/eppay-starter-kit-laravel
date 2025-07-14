# 🛍️ Eppay Marketplace - Free E-commerce Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20.svg)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![Eppay](https://img.shields.io/badge/Eppay-Integrated-4A90E2.svg)](https://eppay.io)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A **free, open-source e-commerce marketplace** platform with built-in **Eppay cryptocurrency payment gateway**. Start accepting USDT payments in minutes with this production-ready Laravel application.

🚀 **Built by the Eppay team to help businesses accept crypto payments easily!**

## 🎯 Why Choose Eppay Marketplace?

- ✅ **100% Free** - No licensing fees, no hidden costs
- ✅ **Eppay Integrated** - Accept USDT payments via QR codes instantly
- ✅ **Multi-Vendor Ready** - Run your own Amazon/eBay style marketplace
- ✅ **Production Ready** - Secure, scalable, and optimized
- ✅ **Modern Tech Stack** - Laravel 12, Tailwind CSS, Alpine.js
- ✅ **Mobile Responsive** - Works perfectly on all devices

## 🖼️ Screenshots

<div align="center">
  <img src="https://via.placeholder.com/800x600" alt="Homepage" width="400"/>
  <img src="https://via.placeholder.com/800x600" alt="Checkout" width="400"/>
  <img src="https://via.placeholder.com/800x600" alt="Seller Dashboard" width="400"/>
  <img src="https://via.placeholder.com/800x600" alt="Payment QR" width="400"/>
</div>

## ✨ Features

### For Marketplace Owners
- 📊 **Admin Dashboard** - Manage sellers, products, and commissions
- 💰 **Commission System** - Set global or per-seller commission rates
- 📈 **Analytics & Reports** - Track sales, revenue, and growth
- 🔐 **Secure Platform** - Built with security best practices

### For Sellers
- 🏪 **Seller Dashboard** - Manage products, orders, and earnings
- 📦 **Inventory Management** - Track stock levels in real-time
- 💳 **Direct Payments** - Receive payments directly to your Eppay wallet
- 📊 **Sales Analytics** - Monitor your store performance

### For Buyers
- 🛒 **Easy Shopping** - Intuitive product browsing and search
- 💎 **Secure Checkout** - Pay with USDT via Eppay QR codes
- 📱 **Mobile Friendly** - Shop on any device
- ⭐ **Reviews & Ratings** - Make informed purchase decisions

## 🚀 Quick Start (5 Minutes!)

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL/SQLite

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/eppay/eppay-marketplace.git
cd eppay-marketplace
```

2. **Install dependencies**
```bash
composer install
npm install && npm run build
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Eppay** (Get your API key from [eppay.io](https://eppay.io))
```env
EPPAY_API_KEY=your_api_key_here
EPPAY_WALLET_ADDRESS=your_wallet_address_here
```

5. **Setup database**
```bash
php artisan migrate --seed
```

6. **Start the server**
```bash
php artisan serve
```

🎉 **That's it!** Visit `http://localhost:8000` to see your marketplace.

## 📚 Documentation

### Setting Up Eppay Payments

1. **Sign up at [eppay.io](https://eppay.io)** - It's free!
2. **Get your API credentials** from the dashboard
3. **Add to your `.env` file**:

```env
# Eppay Configuration
EPPAY_API_KEY=your_api_key_here
EPPAY_API_URL=https://eppay.io
EPPAY_WALLET_ADDRESS=0x_your_wallet_address
EPPAY_RPC_URL=https://chain.scimatic.net
EPPAY_TOKEN_ADDRESS=0x_token_address
```

### Default Login Credentials

After seeding, use these credentials:

**Admin Account**
- Email: `admin@eppay.store`
- Password: `password`

**Demo Seller**
- Email: `tech@seller.com`
- Password: `password`

**Demo Buyer**
- Email: `buyer@example.com`
- Password: `password`


### Project Structure

```
eppay-marketplace/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         # Admin panel controllers
│   │   ├── Seller/        # Seller dashboard controllers
│   │   └── ...            # Buyer controllers
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic (EppayService)
├── resources/
│   └── views/
│       ├── admin/         # Admin panel views
│       ├── seller/        # Seller dashboard views
│       └── ...            # Marketplace views
└── database/
    └── migrations/        # Database structure
```

## 🛠️ Customization Guide

### Changing the Marketplace Name

1. Update `.env`:
```env
APP_NAME="Your Marketplace Name"
```

2. Update logo in `resources/views/layouts/marketplace.blade.php`

### Modifying Commission Rates

Default commission is 10%. Change in `config/marketplace.php`:

```php
'commission_rate' => 0.10, // 10%
```

### Adding Payment Methods

While Eppay is the primary payment method, you can add others:

```php
// app/Services/PaymentService.php
public function processPayment($method, $amount) {
    switch($method) {
        case 'eppay':
            return $this->eppayService->generatePayment($amount);
        case 'your_method':
            // Add your logic
    }
}
```

### Customizing the Theme

The marketplace uses Tailwind CSS. Modify colors in `tailwind.config.js`:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#4A90E2',  // Eppay blue
        secondary: '#F5A623',
        // Add your colors
      }
    }
  }
}
```

## 🚀 Production Deployment

### Quick Deploy with Docker

```bash
docker-compose up -d
```

### Manual Deployment

See our [Production Setup Guide](PRODUCTION.md) for detailed instructions on:
- Server requirements
- Nginx/Apache configuration
- SSL setup
- Performance optimization
- Security hardening

## 📱 Eppay Integration Examples

### Generating Payment QR Code

```php
use App\Services\EppayService;

$eppayService = new EppayService();
$payment = $eppayService->generatePayment(
    amount: 99.99,
    successUrl: 'https://eppay.io/payment-success',
    walletAddress: $seller->eppay_wallet_address
);

// Returns payment ID and QR code data
```

### Checking Payment Status

```php
$status = $eppayService->checkPaymentStatus($paymentId);
if ($status) {
    // Payment completed!
}
```

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🐛 Troubleshooting

### Common Issues

**Eppay payments not working?**
- ✓ Check your API credentials in `.env`
- ✓ Ensure wallet address is valid (starts with 0x)
- ✓ Check Laravel logs: `storage/logs/laravel.log`

**Images not showing?**
```bash
php artisan storage:link
```

**Styles not loading?**
```bash
npm run build
```

## 📞 Support

- 📧 **Email**: support@eppay.io
- 💬 **Discord**: [Join our community](https://discord.gg/eppay)
- 📚 **Docs**: [docs.eppay.io](https://docs.eppay.io)
- 🐛 **Issues**: [GitHub Issues](https://github.com/eppay/eppay-marketplace/issues)

## 🙏 Credits

Built with ❤️ by the [Eppay](https://eppay.io) team to make crypto payments accessible to everyone.

### Technologies Used
- [Laravel](https://laravel.com) - The PHP framework
- [Tailwind CSS](https://tailwindcss.com) - For styling
- [Alpine.js](https://alpinejs.dev) - For interactivity
- [Eppay](https://eppay.io) - Crypto payment gateway

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

<div align="center">
  <h3>Start Accepting Crypto Payments Today!</h3>
  <p>
    <a href="https://eppay.io/register">Get Your Free Eppay Account</a> •
    <a href="https://demo.eppay-marketplace.com">View Demo</a> •
    <a href="https://docs.eppay.io">Documentation</a>
  </p>
  
  <p>Made with ❤️ by <a href="https://eppay.io">Eppay</a></p>
</div>