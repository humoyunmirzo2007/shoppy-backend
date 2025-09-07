CREATE OR REPLACE FUNCTION calculate_payment_type_residue_when_payment_create()
RETURNS TRIGGER AS $$
BEGIN
    -- Update payment_type residue by adding the payment amount
    UPDATE payment_types 
    SET residue = residue + NEW.amount 
    WHERE id = NEW.payment_type_id;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql; 