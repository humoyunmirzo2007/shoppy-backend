CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_invoice_create()
RETURNS TRIGGER AS $$
DECLARE
    current_debt DECIMAL(10,2);
    calculation_value DECIMAL(10,2);
BEGIN
    -- Only process if supplier_id is not null
    IF NEW.supplier_id IS NOT NULL THEN
        -- Get current debt for the supplier
        SELECT debt INTO current_debt
        FROM suppliers
        WHERE id = NEW.supplier_id;
        
        -- Calculate the value based on invoice type
        IF NEW.type = 'SUPPLIER_INPUT' THEN
            -- For supplier input, subtract from debt (negative value)
            calculation_value := -NEW.total_price;
        ELSIF NEW.type = 'SUPPLIER_OUTPUT' THEN
            -- For supplier output, add to debt (positive value)
            calculation_value := NEW.total_price;
        ELSE
            -- For other types, don't calculate
            RETURN NEW;
        END IF;
        
        -- Update supplier debt
        UPDATE suppliers
        SET debt = debt + calculation_value
        WHERE id = NEW.supplier_id;
        
        -- Insert calculation record
        INSERT INTO supplier_calculations (
            supplier_id,
            user_id,
            type,
            value,
            date,
            debt_after_calculation,
            created_at,
            updated_at
        ) VALUES (
            NEW.supplier_id,
            NEW.user_id,
            NEW.type,
            calculation_value,
            NEW.date,
            current_debt + calculation_value,
            NOW(),
            NOW()
        );
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 