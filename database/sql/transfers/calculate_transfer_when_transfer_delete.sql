CREATE OR REPLACE FUNCTION calculate_transfer_when_transfer_delete()
RETURNS TRIGGER AS $$
BEGIN
    -- Only process TRANSFER type payments
    IF OLD.type = 'TRANSFER' THEN
        -- Increase amount back to source payment type
        UPDATE payment_types 
        SET residue = residue + OLD.amount
        WHERE id = OLD.payment_type_id;
        
        -- Decrease amount from destination payment type
        UPDATE payment_types 
        SET residue = residue - OLD.amount
        WHERE id = OLD.other_payment_type_id;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_calculate_transfer_when_transfer_delete
    AFTER DELETE ON payments
    FOR EACH ROW
    EXECUTE FUNCTION calculate_transfer_when_transfer_delete(); 