CREATE OR REPLACE FUNCTION update_product_group_count_when_product_update() RETURNS TRIGGER AS $$
BEGIN
    -- Agar product_group_id o'zgarsa
    IF OLD.product_group_id IS DISTINCT FROM NEW.product_group_id THEN
        -- Eski guruhdan bittani ayirish
        UPDATE product_groups
        SET products_count = products_count - 1
        WHERE id = OLD.product_group_id;
        
        -- Yangi guruhga bittani qo'shish
        UPDATE product_groups
        SET products_count = products_count + 1
        WHERE id = NEW.product_group_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_update_product_group_count_when_product_update
AFTER UPDATE ON products
FOR EACH ROW
EXECUTE FUNCTION update_product_group_count_when_product_update();

