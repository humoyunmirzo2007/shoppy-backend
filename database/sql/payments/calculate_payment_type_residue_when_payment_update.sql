CREATE OR REPLACE FUNCTION calculate_payment_type_residue_when_payment_update()
RETURNS TRIGGER AS $$
BEGIN
    -- If payment_type_id changed, adjust both old and new payment types
    IF OLD.payment_type_id != NEW.payment_type_id THEN
        -- Subtract old amount from old payment type
        UPDATE payment_types 
        SET residue = residue - OLD.amount 
        WHERE id = OLD.payment_type_id;
        
        -- Add new amount to new payment type
        UPDATE payment_types 
        SET residue = residue + NEW.amount 
        WHERE id = NEW.payment_type_id;
    ELSE
        -- Same payment type, adjust for amount difference
        UPDATE payment_types 
        SET residue = residue - OLD.amount + NEW.amount 
        WHERE id = NEW.payment_type_id;
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 