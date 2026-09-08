USE highqhomes;

DELETE FROM `gallery` WHERE `image` LIKE 'https://images.unsplash.com%';

INSERT INTO `gallery` (`title`, `description`, `image`, `category`, `alt_text`, `is_published`, `sort_order`, `created_at`) VALUES
('Luxury Residential Facade', 'Exterior view of a premium multi-story residential build in Garowe.', 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80', 'residential', 'Luxury residential building exterior in Garowe', 1, 1, NOW()),
('Residential Living Space', 'Finished living area with premium interior detailing.', 'https://images.unsplash.com/photo-1600210492486-724fe41c17f7?w=1200&q=80', 'interior', 'Modern residential living room interior', 1, 2, NOW()),
('Urban Apartment Complex', 'Contemporary apartment development with clean architectural lines.', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80', 'residential', 'Urban apartment building exterior', 1, 3, NOW()),
('Apartment Interior Finish', 'Open-plan apartment interior with quality fittings.', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80', 'interior', 'Apartment interior with premium finishes', 1, 4, NOW()),
('Corporate Office Building', 'Commercial office structure with modern glass and cladding.', 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&q=80', 'commercial', 'Corporate office building exterior', 1, 5, NOW()),
('Office Workspace', 'Commercial interior workspace with professional fit-out.', 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=1200&q=80', 'commercial', 'Corporate office interior workspace', 1, 6, NOW()),
('Community Mosque Exterior', 'Completed community mosque with refined architectural form.', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=80', 'mosque', 'Community mosque exterior in Puntland', 1, 7, NOW()),
('Mosque Prayer Hall', 'Interior view showing acoustic finishes and lighting.', 'https://images.unsplash.com/photo-1564760055775-d63ef17a55c4?w=1200&q=80', 'interior', 'Mosque prayer hall interior', 1, 8, NOW()),
('Structural Build Phase', 'Reinforced concrete structure during active construction.', 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=80', 'construction', 'Building under construction with concrete structure', 1, 9, NOW()),
('Site Planning Overview', 'Aerial perspective of a planned development site.', 'https://images.unsplash.com/photo-1524661135-423995f22d0b?w=1200&q=80', 'construction', 'Construction site aerial planning view', 1, 10, NOW()),
('Exterior Textured Finish', 'Textured exterior wall coating applied on site.', 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=1200&q=80', 'exterior', 'Textured exterior wall finish on building', 1, 11, NOW()),
('Modern Villa Exterior', 'Standalone villa with landscaped approach and premium facade.', 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80', 'exterior', 'Modern villa exterior with landscaping', 1, 12, NOW()),
('Kitchen Fit-Out', 'Custom kitchen installation with stone and cabinetry.', 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1200&q=80', 'interior', 'Premium kitchen interior fit-out', 1, 13, NOW()),
('Master Bedroom Finish', 'Bedroom suite with coordinated finishes and lighting.', 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200&q=80', 'interior', 'Master bedroom with premium interior design', 1, 14, NOW()),
('Landscape & Hardscape', 'Outdoor hardscape and planting around a residential compound.', 'https://images.unsplash.com/photo-1558904541-efa843a96f01?w=1200&q=80', 'landscape', 'Residential landscape and hardscape design', 1, 15, NOW()),
('Commercial Entrance', 'Grand entrance detailing for a commercial development.', 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80', 'commercial', 'Commercial building entrance and lobby area', 1, 16, NOW()),
('Premium Bathroom Finish', 'Bathroom fit-out with tile, fixtures, and ventilation.', 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=1200&q=80', 'finishing', 'Premium bathroom finishing details', 1, 17, NOW()),
('Staircase & Joinery', 'Custom staircase and joinery craftsmanship on site.', 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&q=80', 'finishing', 'Custom staircase and wood joinery', 1, 18, NOW()),
('High-Rise Progress', 'Multi-floor residential tower during finishing phase.', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80', 'residential', 'High-rise residential tower construction progress', 1, 19, NOW()),
('Team on Site', 'HighQ Homes site team during quality inspection.', 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=1200&q=80', 'construction', 'Construction team reviewing work on site', 1, 20, NOW()),
('Handover Walkthrough', 'Final client walkthrough before project handover.', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80', 'finishing', 'Project handover walkthrough with client', 1, 21, NOW()),
('Retail Frontage', 'Completed retail frontage with signage-ready facade.', 'https://images.unsplash.com/photo-1449844908441-8829872d2607?w=1200&q=80', 'commercial', 'Retail commercial building frontage', 1, 22, NOW()),
('Compound Gate & Wall', 'Security wall and entrance gate for a residential compound.', 'https://images.unsplash.com/photo-1605276374102-dee2a0ed2cd6?w=1200&q=80', 'exterior', 'Residential compound gate and boundary wall', 1, 23, NOW()),
('Ceiling & Lighting Detail', 'Recessed lighting and ceiling finish in a commercial space.', 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=1200&q=80', 'finishing', 'Ceiling and lighting finishing detail', 1, 24, NOW());
