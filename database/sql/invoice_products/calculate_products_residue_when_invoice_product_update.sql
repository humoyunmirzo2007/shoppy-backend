CREATE OR REPLACE FUNCTION calculate_products_residue_when_invoice_product_update()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.count <> NEW.count THEN
UPDATE products
SET residue = residue - OLD.count + NEW.count
WHERE id = NEW.product_id;
END IF;

RETURN NEW;
END;
$$ LANGUAGE plpgsql;
