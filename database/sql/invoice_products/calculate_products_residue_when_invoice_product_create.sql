CREATE OR REPLACE FUNCTION calculate_products_residue_when_invoice_product_create () RETURNS TRIGGER AS $$
BEGIN
UPDATE products
SET residue = residue + NEW.count
WHERE id = NEW.product_id;

RETURN NEW;
END;
$$ LANGUAGE plpgsql;