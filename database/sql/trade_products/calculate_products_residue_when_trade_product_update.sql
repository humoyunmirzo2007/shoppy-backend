CREATE OR REPLACE FUNCTION calculate_products_residue_when_trade_product_update () RETURNS TRIGGER AS $$
BEGIN
UPDATE products
SET residue = residue - OLD.count + NEW.count
WHERE id = NEW.product_id;

RETURN NEW;
END;
$$ LANGUAGE plpgsql; 