CREATE OR REPLACE FUNCTION calculate_client_debt_when_trade_create () RETURNS TRIGGER AS $$
BEGIN
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