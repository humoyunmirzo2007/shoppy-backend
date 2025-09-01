CREATE OR REPLACE FUNCTION calculate_supplier_debt_when_invoice_update()
RETURNS TRIGGER AS $$
DECLARE
    current_debt DECIMAL(10,2);
    old_calculation_value DECIMAL(10,2);
    new_calculation_value DECIMAL(10,2);
    calculation_difference DECIMAL(10,2);
BEGIN
    -- Only process if supplier_id is not null and relevant fields changed
    IF NEW.supplier_id IS NOT NULL AND (
        OLD.total_price != NEW.total_price OR 
        OLD.type != NEW.type OR 
        OLD.supplier_id != NEW.supplier_id
    ) THEN
        
        -- First, reverse the old calculation
        IF OLD.supplier_id IS NOT NULL THEN
            IF OLD.type = 'SUPPLIER_INPUT' THEN
                old_calculation_value := -OLD.total_price;
            ELSIF OLD.type = 'SUPPLIER_OUTPUT' THEN
                old_calculation_value := OLD.total_price;
            ELSE
                old_calculation_value := 0;
            END IF;
            
            -- Update supplier debt by reversing old calculation
            UPDATE suppliers
            SET debt = debt - old_calculation_value
            WHERE id = OLD.supplier_id;
        END IF;
        
        -- Get current debt for the new supplier
        SELECT debt INTO current_debt
        FROM suppliers
        WHERE id = NEW.supplier_id;
        
        -- Calculate the new value based on invoice type
        IF NEW.type = 'SUPPLIER_INPUT' THEN
            new_calculation_value := -NEW.total_price;
        ELSIF NEW.type = 'SUPPLIER_OUTPUT' THEN
            new_calculation_value := NEW.total_price;
        ELSE
            new_calculation_value := 0;
        END IF;
        
        -- Update supplier debt with new calculation
        UPDATE suppliers
        SET debt = debt + new_calculation_value
        WHERE id = NEW.supplier_id;
        
        -- Update or insert calculation record
        IF EXISTS (
            SELECT 1 FROM supplier_calculations 
            WHERE supplier_id = NEW.supplier_id 
            AND type = NEW.type 
            AND date = NEW.date
        ) THEN
            -- Update existing record
            UPDATE supplier_calculations
            SET 
                value = new_calculation_value,
                debt_after_calculation = current_debt + new_calculation_value,
                updated_at = NOW()
            WHERE supplier_id = NEW.supplier_id 
            AND type = NEW.type 
            AND date = NEW.date;
        ELSE
            -- Insert new record
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
                new_calculation_value,
                NEW.date,
                current_debt + new_calculation_value,
                NOW(),
                NOW()
            );
        END IF;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 