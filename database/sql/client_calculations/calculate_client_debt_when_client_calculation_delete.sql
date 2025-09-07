CREATE OR REPLACE FUNCTION calculate_client_debt_when_client_calculation_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Reverse the client debt calculation
    UPDATE clients
    SET debt = debt - OLD.value
    WHERE id = OLD.client_id;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 