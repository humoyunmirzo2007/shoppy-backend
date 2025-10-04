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

CREATE OR REPLACE TRIGGER trg_supplier_calculation_create
AFTER INSERT ON supplier_calculations
FOR EACH ROW
EXECUTE FUNCTION calculate_supplier_debt_when_supplier_calculation_create(); 