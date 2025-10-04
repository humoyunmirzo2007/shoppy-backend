CREATE OR REPLACE FUNCTION calculate_client_debt_when_client_calculation_update()
RETURNS TRIGGER AS $$
DECLARE
    current_debt DECIMAL(10,2);
    debt_difference DECIMAL(10,2);
BEGIN
    -- Calculate the difference between old and new values
    debt_difference := NEW.value - OLD.value;
    
    -- If client changed, handle both old and new clients
    IF OLD.client_id != NEW.client_id THEN
        -- Reverse old client debt
        UPDATE clients
        SET debt = debt - OLD.value
        WHERE id = OLD.client_id;
        
        -- Apply new client debt
        SELECT debt INTO current_debt
        FROM clients
        WHERE id = NEW.client_id;
        
        UPDATE clients
        SET debt = debt + NEW.value
        WHERE id = NEW.client_id;
    ELSE
        -- Same client, just update the difference
        UPDATE clients
        SET debt = debt + debt_difference
        WHERE id = NEW.client_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trg_client_calculation_update
AFTER UPDATE ON client_calculations
FOR EACH ROW
EXECUTE FUNCTION calculate_client_debt_when_client_calculation_update(); 