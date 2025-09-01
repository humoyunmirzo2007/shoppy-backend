CREATE OR REPLACE FUNCTION calculate_client_debt_when_trade_delete () RETURNS TRIGGER AS $$
BEGIN
    -- Trade o'chirilganda calculation ham o'chiriladi va debt ni qaytarish
    IF OLD.type = 'TRADE' THEN
        UPDATE clients SET debt = debt - OLD.total_price WHERE id = OLD.client_id;
    END IF;
    
    IF OLD.type = 'RETURN_PRODUCT' THEN
        UPDATE clients SET debt = debt + OLD.total_price WHERE id = OLD.client_id;
    END IF;
    
    DELETE FROM client_calculations WHERE trade_id = OLD.id;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 