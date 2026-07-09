-- ============================================================
-- CookSpace admin seed data
-- Login credentials:
--   Admin: erwan@gmail.com / 12345678
-- ============================================================

TRUNCATE TABLE users CASCADE;

INSERT INTO users (name, email, role, is_suspended, country, currency, wallet_balance, bio, phone, email_verified_at, password, created_at, updated_at) VALUES
('Erwan (Admin)', 'erwan@gmail.com', 'admin', FALSE, 'ID', 'IDR', 0.00, NULL, '+6281200000000', NOW(), '$2y$10$b6I.TnrDg1xsI.D1oYSEI.M4gWSDJAgcEEUKPZ1y3b0UlNCEyxd6S', NOW(), NOW());
