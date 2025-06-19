<h4>Repair Orders</h4>
<a href="<?php echo admin_url('repair_checkin/repair_checkin/add'); ?>" class="btn btn-primary">New Repair Order</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Device</th>
            <th>Issue</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($repair_orders as $order): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo get_company_name($order['customer_id']); ?></td>
            <td><?php echo $order['make_model']; ?></td>
            <td><?php echo $order['issue_description']; ?></td>
            <td><?php echo $order['status'] ?? 'Pending'; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
