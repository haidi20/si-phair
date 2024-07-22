const { Op } = require('sequelize');
const jobOrder = require('../models/jobOrder');
const socketioModule = require('../socketioModule');
const Notification = require('../models/notification');

exports.store = async (now, userId) => {
    const jobOrders = await jobOrder.findAll({
        where: {
            datetime_estimation_end: {
                [Op.lte]: now,
            },
            status: 'active',
            created_by: userId,
            deleted_at: {
                [Op.is]: null,
            }
        },
        // limit: 5,
        order: [
            ['datetime_estimation_end', 'DESC'] // Opsional: Mengurutkan berdasarkan datetime_estimation_end secara descending
        ],
    });

    const promise = jobOrders.map(async jobOrder => {
        const newItem = {
            user_id: userId,
            parent_id: jobOrder.id,
            parent_model: "jobOrder",
        };

        // Find or create the notification with the specified attributes
        const [foundItem, created] = await Notification.findOrCreate({
            where: newItem,
            defaults: newItem, // This ensures that the newItem will be used as the defaults when creating a new record
        });

        // If the record was created (i.e., it didn't already exist), you can do something with it
        if (created) {
            // Do something when the record is newly created

            await Notification.create(newItem);
        } else {
            // Do something when the record already exists
        }
    });

    return Promise.all(promise);
}

exports.sendMessage = (req, res, params) => {
    // This is where you'd get your message from the request, e.g. req.body.message
    const message = req.body?.message;
    // const user_id = req.body?.user_id;

    const io = socketioModule.getIo();
    // Emit the message to all connected clients
    io.emit('notification', {
        message
    });

    res.json({ message: `Message sent! ${message}` });
}
