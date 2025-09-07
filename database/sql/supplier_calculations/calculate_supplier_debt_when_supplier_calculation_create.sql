CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_supplier_calculation_create()
RETURNS TRIGGER AS $$
BEGIN
    -- Update supplier debt
    UPDATE suppliers
    SET debt = debt + NEW.value
    WHERE id = NEW.supplier_id;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 