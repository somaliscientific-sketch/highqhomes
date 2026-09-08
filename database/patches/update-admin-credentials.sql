USE `highqhomes`;

UPDATE `users`
SET
  `email` = 'info@highqhomes.net',
  `password` = '$2y$12$Kky.BLZEDlTzj4umSRnzE.R1eEeH.q/AeA1.dwf.IyAEEgJ3Ek9FS',
  `name` = 'ICT Admin',
  `role` = 'super_admin',
  `is_active` = 1
WHERE `email` IN ('info@highqhomes.com', 'info@highqhomes.net')
   OR `id` = 2;
