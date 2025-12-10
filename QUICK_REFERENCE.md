# Customer Session System - Quick Reference

## What Was Implemented

A complete customer session system with 30-minute token expiration and table-based order isolation. No login required.

## Key Features

✅ **Anonymous Sessions** - Each device gets a unique token stored in localStorage  
✅ **30-Minute Timeout** - Sessions expire automatically  
✅ **Table Isolation** - Orders filtered by table + token  
✅ **No History Leaks** - Customers never see previous customers' orders  
✅ **Auto-Payment** - All pending orders for session paid together  
✅ **Persistent** - Token survives page refresh within timeout  

## Files Modified

### Backend (Laravel)
1. **Migration**: `database/migrations/2025_12_09_152410_add_customer_session_fields_to_orders_table.php`
   - Added `customer_token` field
   - Changed status enum: 'unpaid' → 'pending'

2. **Model**: `app/Models/Order.php`
   - Added `customer_token` to fillable
   - Updated status checks

3. **Controller**: `app/Http/Controllers/OrderController.php`
   - `store()`: Accept and store customer_token
   - `markPaid()`: Mark all pending orders for table+token as paid
   - `historyByCustomer()`: Filter by table+token, return only paid orders

4. **Controller**: `app/Http/Controllers/PaymentController.php`
   - Updated status check to 'pending'

### Frontend (React)
1. **New Utility**: `src/utils/customerSession.js`
   - `getOrCreateCustomerSession(tableNumber)` - Main function
   - Generates/validates/reuses tokens
   - 30-minute expiration logic

2. **Payment Page**: `src/pages/Payment.jsx`
   - Get customer token before order creation
   - Include token in order payload

3. **Confirm Page**: `src/pages/ConfirmQR.jsx`
   - Automatically trigger payment on mount
   - Send table + token to payment API

4. **History Page**: `src/pages/History.jsx`
   - Get token from session
   - Filter history by table + token

5. **API Client**: `src/lib/api.js`
   - Updated `payOrder` to accept payload

## How It Works

### Token Generation
```javascript
Token Format: cust_<timestamp>_<random>
Example: cust_1702123456_xyz789

Stored in localStorage:
{
  "token": "cust_1702123456_xyz789",
  "created_at": 1702123456000,
  "table": "5"
}
```

### Token Reuse Logic
Token is reused ONLY IF:
- Age ≤ 30 minutes AND
- Table number matches

Otherwise, generate NEW token.

### Database Structure
```sql
orders table:
- customer_token VARCHAR(255) INDEXED
- table_number VARCHAR(255) INDEXED  
- status ENUM('pending', 'paid', 'expired', 'cancelled')
```

### Data Isolation
```sql
-- History query
SELECT * FROM orders 
WHERE table_number = ? 
  AND customer_token = ? 
  AND status = 'paid'
ORDER BY paid_at DESC;

-- Payment update
UPDATE orders 
SET status = 'paid', paid_at = NOW() 
WHERE table_number = ? 
  AND customer_token = ? 
  AND status = 'pending';
```

## Usage Examples

### Creating Order
```javascript
import { getOrCreateCustomerSession } from '../utils/customerSession';

const customerToken = getOrCreateCustomerSession(tableNumber);

const payload = {
  table_number: tableNumber,
  customer_token: customerToken,
  items: [...],
  // ... other fields
};

await createOrder(payload);
```

### Paying Orders
```javascript
const customerToken = getOrCreateCustomerSession(table);

await payOrder(orderId, {
  table_number: table,
  customer_token: customerToken
});
```

### Fetching History
```javascript
const customerToken = getOrCreateCustomerSession(tableNumber);

const response = await getCustomerHistory({
  table: tableNumber,
  token: customerToken
});
```

## Migration Steps

1. **Run Migration**
   ```bash
   cd ApiCobekOrder
   php artisan migrate
   ```

2. **Verify Database**
   ```sql
   DESCRIBE orders;
   -- Should see: customer_token, status ENUM with 'pending'
   ```

3. **Test Frontend**
   ```bash
   cd tesDesign
   npm run dev
   ```

## Testing Checklist

- [ ] New customer gets unique token
- [ ] Token persists on page refresh
- [ ] Token reused within 30 minutes
- [ ] New token after 30 minutes
- [ ] New token when table changes
- [ ] Order created with token
- [ ] Payment marks all pending orders as paid
- [ ] History shows only current session's orders
- [ ] Different customers at same table isolated

## API Endpoints

### Create Order
```
POST /api/orders
Body: { table_number, customer_token, items, ... }
```

### Pay Orders
```
PATCH /api/orders/{id}/pay
Body: { table_number, customer_token }
```

### Get History
```
GET /api/customers/history?table={table}&token={token}
```

## Troubleshooting

**Token not persisting?**
- Check localStorage is enabled
- Check browser console for errors

**History showing wrong orders?**
- Verify API sends both table + token
- Check backend filtering logic

**Payment not working?**
- Verify orders have matching table + token
- Check order status is 'pending'

## Documentation

For detailed implementation guide, see:
**CUSTOMER_SESSION_IMPLEMENTATION.md**

---

**Implementation Complete** ✅  
All requirements met. System ready for testing and deployment.
