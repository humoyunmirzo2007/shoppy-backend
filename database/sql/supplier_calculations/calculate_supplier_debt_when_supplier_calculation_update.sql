CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_supplier_calculation_update()
RETURNS TRIGGER AS $$
DECLARE
    current_debt DECIMAL(10,2);
    debt_difference DECIMAL(10,2);
BEGIN
    -- Calculate the difference between old and new values
    debt_difference := NEW.value - OLD.value;
    
    -- If supplier changed, handle both old and new suppliers
    IF OLD.supplier_id != NEW.supplier_id THEN
        -- Reverse old supplier debt
        UPDATE suppliers
        SET debt = debt - OLD.value
        WHERE id = OLD.supplier_id;
        
        -- Apply new supplier debt
        SELECT debt INTO current_debt
        FROM suppliers
        WHERE id = NEW.supplier_id;
        
        UPDATE suppliers
        SET debt = debt + NEW.value
        WHERE id = NEW.supplier_id;
    ELSE
        -- Same supplier, just update the difference
        UPDATE suppliers
        SET debt = debt + debt_difference
        WHERE id = NEW.supplier_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 