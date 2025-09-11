CREATE OR REPLACE FUNCTION calculate_money_operation_effects_when_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Revert operation effects
    IF OLD.type = 'TRANSFER' THEN
        -- Revert transfer effects
        UPDATE payment_types 
        SET residue = residue + OLD.amount 
        WHERE id = OLD.payment_type_id;
        
        UPDATE payment_types 
        SET residue = residue - OLD.amount 
        WHERE id = OLD.other_payment_type_id;
        
    ELSIF OLD.operation_type = 'input' THEN
        -- Revert payment increase
        UPDATE payment_types 
        SET residue = residue - OLD.amount 
        WHERE id = OLD.payment_type_id;
        
    ELSIF OLD.operation_type = 'output' THEN
        -- Revert payment decrease
        UPDATE payment_types 
        SET residue = residue + OLD.amount 
        WHERE id = OLD.payment_type_id;
        
        -- Revert debt changes
        IF OLD.type = 'CLIENT_PAYMENT_OUTPUT' AND OLD.client_id IS NOT NULL THEN
            UPDATE clients 
            SET debt = debt - OLD.amount 
            WHERE id = OLD.client_id;
        ELSIF OLD.type = 'SUPPLIER_PAYMENT_OUTPUT' AND OLD.supplier_id IS NOT NULL THEN
            UPDATE suppliers 
            SET debt = debt - OLD.amount 
            WHERE id = OLD.supplier_id;
        END IF;
    END IF;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 