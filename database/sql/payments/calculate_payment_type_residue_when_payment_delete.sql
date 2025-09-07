CREATE OR REPLACE FUNCTION calculate_payment_type_residue_when_payment_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Subtract payment amount from payment_type residue
    UPDATE payment_types 
    SET residue = residue - OLD.amount 
    WHERE id = OLD.payment_type_id;
    
    RETURN OLD;
END;
$$ LANGUAGE plpgsql; 