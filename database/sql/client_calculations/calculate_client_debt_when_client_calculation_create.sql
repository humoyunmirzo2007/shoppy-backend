CREATE OR REPLACE FUNCTION calculate_client_debt_when_client_calculation_create()
RETURNS TRIGGER AS $$
BEGIN
    -- Update client debt
    UPDATE clients
    SET debt = debt + NEW.value
    WHERE id = NEW.client_id;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 