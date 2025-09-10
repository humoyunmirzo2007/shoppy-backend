CREATE OR REPLACE FUNCTION calculate_transfer_when_transfer_create()
RETURNS TRIGGER AS $$
BEGIN
    -- Only process TRANSFER type payments
    IF NEW.type = 'TRANSFER' THEN
        -- Decrease amount from source payment type
        UPDATE payment_types 
        SET residue = residue - NEW.amount
        WHERE id = NEW.payment_type_id;
        
        -- Increase amount to destination payment type
        UPDATE payment_types 
        SET residue = residue + NEW.amount
        WHERE id = NEW.other_payment_type_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_calculate_transfer_when_transfer_create
    AFTER INSERT ON payments
    FOR EACH ROW
    EXECUTE FUNCTION calculate_transfer_when_transfer_create(); 