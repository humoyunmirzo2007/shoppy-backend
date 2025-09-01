CREATE OR REPLACE FUNCTION calculate_products_residue_when_trade_product_delete () RETURNS TRIGGER AS $$
BEGIN
UPDATE products
SET residue = residue - OLD.count
WHERE id = OLD.product_id;
 
RETURN OLD;
END;
$$ LANGUAGE plpgsql; 