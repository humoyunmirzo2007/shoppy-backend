CREATE OR REPLACE FUNCTION calculate_products_residue_when_invoice_product_create()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE products
    SET residue = residue + NEW.count
    WHERE id = NEW.product_id;

    IF NEW.count > 0 THEN
        UPDATE products
        SET price = NEW.price,
            input_price = NEW.input_price,
            markup = ((NEW.price - NEW.input_price) / NEW.input_price) * 100
        WHERE id = NEW.product_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trg_invoice_product_create
AFTER INSERT ON invoice_products
FOR EACH ROW
EXECUTE FUNCTION calculate_products_residue_when_invoice_product_create();
