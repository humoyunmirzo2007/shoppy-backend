CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_invoice_delete()
RETURNS TRIGGER AS $$
DECLARE
    calculation_value DECIMAL(10,2);
BEGIN
    -- Only process if supplier_id is not null
    IF OLD.supplier_id IS NOT NULL THEN
        -- Calculate the value based on invoice type to reverse it
        IF OLD.type = 'SUPPLIER_INPUT' THEN
            -- For supplier input, we need to add back to debt (reverse the negative)
            calculation_value := OLD.total_price;
        ELSIF OLD.type = 'SUPPLIER_OUTPUT' THEN
            -- For supplier output, we need to subtract from debt (reverse the positive)
            calculation_value := -OLD.total_price;
        ELSE
            -- For other types, don't calculate
            RETURN OLD;
        END IF;
        
        -- Update supplier debt by reversing the calculation
        UPDATE suppliers
        SET debt = debt + calculation_value
        WHERE id = OLD.supplier_id;
        
        -- Delete calculation record
        DELETE FROM supplier_calculations
        WHERE supplier_id = OLD.supplier_id 
        AND type = OLD.type 
        AND date = OLD.date;
    END IF;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 