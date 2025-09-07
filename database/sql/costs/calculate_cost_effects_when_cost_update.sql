CREATE OR REPLACE FUNCTION calculate_cost_effects_when_cost_update()
RETURNS TRIGGER AS $$
BEGIN
    -- Revert old cost effects on payment_type
    UPDATE payment_types 
    SET residue = residue + OLD.amount 
    WHERE id = OLD.payment_type_id;
    
    -- Revert old cost effects on clients/suppliers
    IF OLD.type = 'CLIENT_COST' AND OLD.client_id IS NOT NULL THEN
        UPDATE clients 
        SET debt = debt - OLD.amount 
        WHERE id = OLD.client_id;
    ELSIF OLD.type = 'SUPPLIER_COST' AND OLD.supplier_id IS NOT NULL THEN
        UPDATE suppliers 
        SET debt = debt - OLD.amount 
        WHERE id = OLD.supplier_id;
    END IF;
    
    -- Apply new cost effects on payment_type
    UPDATE payment_types 
    SET residue = residue - NEW.amount 
    WHERE id = NEW.payment_type_id;
    
    -- Apply new cost effects on clients/suppliers
    IF NEW.type = 'CLIENT_COST' AND NEW.client_id IS NOT NULL THEN
        UPDATE clients 
        SET debt = debt + NEW.amount 
        WHERE id = NEW.client_id;
    ELSIF NEW.type = 'SUPPLIER_COST' AND NEW.supplier_id IS NOT NULL THEN
        UPDATE suppliers 
        SET debt = debt + NEW.amount 
        WHERE id = NEW.supplier_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 