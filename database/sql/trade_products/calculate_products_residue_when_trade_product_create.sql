CREATE OR REPLACE FUNCTION calculate_products_residue_when_trade_product_create () RETURNS TRIGGER AS $$
BEGIN
UPDATE products
SET residue = residue + NEW.count
WHERE id = NEW.product_id;
 
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_calculate_products_residue_when_trade_product_create
AFTER INSERT ON trade_products
FOR EACH ROW
EXECUTE FUNCTION calculate_products_residue_when_trade_product_create(); 