CREATE OR REPLACE FUNCTION update_product_group_count_when_product_delete() RETURNS TRIGGER AS $$
BEGIN
    UPDATE product_groups
    SET products_count = products_count - 1
    WHERE id = OLD.product_group_id;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_update_product_group_count_when_product_delete
AFTER DELETE ON products
FOR EACH ROW
EXECUTE FUNCTION update_product_group_count_when_product_delete();

