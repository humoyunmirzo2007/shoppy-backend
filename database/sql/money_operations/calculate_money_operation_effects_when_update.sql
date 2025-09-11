CREATE OR REPLACE FUNCTION calculate_money_operation_effects_when_update()
RETURNS TRIGGER AS $$
BEGIN
    -- Revert old operation effects
    IF OLD.type = 'TRANSFER' THEN
        -- Revert old transfer effects
        UPDATE payment_types 
        SET residue = residue + OLD.amount 
        WHERE id = OLD.payment_type_id;
        
        UPDATE payment_types 
        SET residue = residue - OLD.amount 
        WHERE id = OLD.other_payment_type_id;
        
    ELSIF OLD.operation_type = 'input' THEN
        -- Revert old payment increase
        UPDATE payment_types 
        SET residue = residue - OLD.amount 
        WHERE id = OLD.payment_type_id;
        
    ELSIF OLD.operation_type = 'output' THEN
        -- Revert old payment decrease
        UPDATE payment_types 
        SET residue = residue + OLD.amount 
        WHERE id = OLD.payment_type_id;
        
        -- Revert old debt changes
        IF OLD.type = 'CLIENT_PAYMET_OUTPUT' AND OLD.client_id IS NOT NULL THEN
            UPDATE clients 
            SET debt = debt - OLD.amount 
            WHERE id = OLD.client_id;
        ELSIF OLD.type = 'SUPPLIER_PAYMET_OUTPUT' AND OLD.supplier_id IS NOT NULL THEN
            UPDATE suppliers 
            SET debt = debt - OLD.amount 
            WHERE id = OLD.supplier_id;
        END IF;
    END IF;
    
    -- Apply new operation effects
    IF NEW.type = 'TRANSFER' THEN
        -- Apply new transfer effects
        UPDATE payment_types 
        SET residue = residue - NEW.amount 
        WHERE id = NEW.payment_type_id;
        
        UPDATE payment_types 
        SET residue = residue + NEW.amount 
        WHERE id = NEW.other_payment_type_id;
        
    ELSIF NEW.operation_type = 'input' THEN
        -- Apply new payment increase
        UPDATE payment_types 
        SET residue = residue + NEW.amount 
        WHERE id = NEW.payment_type_id;
        
    ELSIF NEW.operation_type = 'output' THEN
        -- Apply new payment decrease
        UPDATE payment_types 
        SET residue = residue - NEW.amount 
        WHERE id = NEW.payment_type_id;
        
        -- Apply new debt changes
        IF NEW.type = 'CLIENT_PAYMET_OUTPUT' AND NEW.client_id IS NOT NULL THEN
            UPDATE clients 
            SET debt = debt + NEW.amount 
            WHERE id = NEW.client_id;
        ELSIF NEW.type = 'SUPPLIER_PAYMET_OUTPUT' AND NEW.supplier_id IS NOT NULL THEN
            UPDATE suppliers 
            SET debt = debt + NEW.amount 
            WHERE id = NEW.supplier_id;
        END IF;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 