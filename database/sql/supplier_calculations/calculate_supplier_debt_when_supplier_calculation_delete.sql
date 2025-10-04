CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_supplier_calculation_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Reverse the supplier debt calculation
    UPDATE suppliers
    SET debt = debt - OLD.value
    WHERE id = OLD.supplier_id;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trg_supplier_calculation_delete
BEFORE DELETE ON supplier_calculations
FOR EACH ROW
EXECUTE FUNCTION calculate_supplier_debt_when_supplier_calculation_delete(); 