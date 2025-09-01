CREATE OR REPLACE FUNCTION calculate_client_debt_when_trade_update () RETURNS TRIGGER AS $$
BEGIN
    -- Eski calculation ni o'chirish va debt ni qaytarish
    IF OLD.type = 'TRADE' THEN
        UPDATE clients SET debt = debt - OLD.total_price WHERE id = OLD.client_id;
    END IF;
    
    IF OLD.type = 'RETURN_PRODUCT' THEN
        UPDATE clients SET debt = debt + OLD.total_price WHERE id = OLD.client_id;
    END IF;
    
    DELETE FROM client_calculations WHERE trade_id = NEW.id;

    -- Yangi calculation qo'shish va debt ni yangilash
    -- TRADE bo'lsa, mijoz qarzi ko'payadi
    IF NEW.type = 'TRADE' THEN
        INSERT INTO client_calculations (client_id, trade_id, type, value, date)
        VALUES (NEW.client_id, NEW.id, 'TRADE', NEW.total_price, NEW.date);
        
        -- Client debt ni yangilash
        UPDATE clients SET debt = debt + NEW.total_price WHERE id = NEW.client_id;
    END IF;

    -- RETURN_PRODUCT bo'lsa, mijoz qarzi kamayadi
    IF NEW.type = 'RETURN_PRODUCT' THEN
        INSERT INTO client_calculations (client_id, trade_id, type, value, date)
        VALUES (NEW.client_id, NEW.id, 'RETURN_PRODUCT', -NEW.total_price, NEW.date);
        
        -- Client debt ni yangilash
        UPDATE clients SET debt = debt - NEW.total_price WHERE id = NEW.client_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 