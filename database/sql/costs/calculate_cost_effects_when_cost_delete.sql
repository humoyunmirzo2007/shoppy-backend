CREATE OR REPLACE FUNCTION calculate_cost_effects_when_cost_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Revert payment_type residue (add back the amount that was deducted)
    UPDATE payment_types 
    SET residue = residue + OLD.amount 
    WHERE id = OLD.payment_type_id;
    
    -- Revert client/supplier debt based on cost type
    IF OLD.type = 'CLIENT_COST' AND OLD.client_id IS NOT NULL THEN
        -- Decrease client debt (client owes less)
        UPDATE clients 
        SET debt = debt - OLD.amount 
        WHERE id = OLD.client_id;
    ELSIF OLD.type = 'SUPPLIER_COST' AND OLD.supplier_id IS NOT NULL THEN
        -- Decrease supplier debt (we owe supplier less)
        UPDATE suppliers 
        SET debt = debt - OLD.amount 
        WHERE id = OLD.supplier_id;
    END IF;
    -- For OTHER_COST, only payment_type residue is reverted
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 