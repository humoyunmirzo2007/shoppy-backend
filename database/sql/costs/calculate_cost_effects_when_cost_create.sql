CREATE OR REPLACE FUNCTION calculate_cost_effects_when_cost_create()
RETURNS TRIGGER AS $$
BEGIN
    -- Always decrease payment_type residue (cost reduces available funds)
    UPDATE payment_types 
    SET residue = residue - NEW.amount 
    WHERE id = NEW.payment_type_id;
    
    -- Handle client/supplier debt based on cost type
    IF NEW.type = 'CLIENT_PAYMET_OUTPUT' AND NEW.client_id IS NOT NULL THEN
        -- Increase client debt (client owes more)
        UPDATE clients 
        SET debt = debt + NEW.amount 
        WHERE id = NEW.client_id;
    ELSIF NEW.type = 'SUPPLIER_PAYMET_OUTPUT' AND NEW.supplier_id IS NOT NULL THEN
        -- Increase supplier debt (we owe supplier more)
        UPDATE suppliers 
        SET debt = debt + NEW.amount 
        WHERE id = NEW.supplier_id;
    END IF;
    -- For OTHER_PAYMET_OUTPUT, only payment_type residue is affected
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 