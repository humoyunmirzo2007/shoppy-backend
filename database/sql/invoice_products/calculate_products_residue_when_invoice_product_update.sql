CREATE OR REPLACE FUNCTION calculate_products_residue_when_invoice_product_update()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.count <> NEW.count THEN
        UPDATE products
        SET residue = residue - OLD.count + NEW.count
        WHERE id = NEW.product_id;
    END IF;

    IF NEW.count > 0 THEN
        UPDATE products
        SET input_price = NEW.input_price,
            price = NEW.price,
            markup = ((NEW.price - NEW.input_price) / NEW.input_price) * 100,
            wholesale_price = NEW.wholesale_price,
            wholesale_markup = ((NEW.wholesale_price - NEW.input_price) / NULLIF(NEW.input_price, 0)) * 100
        WHERE id = NEW.product_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trg_invoice_product_update
AFTER UPDATE ON invoice_products
FOR EACH ROW
EXECUTE FUNCTION calculate_products_residue_when_invoice_product_update();
