const { Sequelize, DataTypes, Op } = require('sequelize');
const db = require('../database');
const JobOrder = require('./jobOrder');
const sequelize = require('../database');

const Notification = db.define('Notification', {
    id: {
        type: DataTypes.BIGINT,
        autoIncrement: true,
        primaryKey: true
    },
    user_id: {
        type: DataTypes.BIGINT,
        allowNull: false
    },
    parent_id: {
        type: DataTypes.BIGINT,
        allowNull: false
    },
    parent_model: {
        type: DataTypes.STRING,
        allowNull: false
    },
    readed_at: {
        type: DataTypes.DATE,
        allowNull: true
    },
    is_show: {
        type: DataTypes.BOOLEAN,
        defaultValue: false
    },
    created_at: {
        type: DataTypes.DATE,
        allowNull: false
    },
    updated_at: {
        type: DataTypes.DATE,
        allowNull: false
    },
}, {
    tableName: 'notifications',
    underscored: true,
    timestamps: true,   // this option states that we are using Sequelize's built-in timestamps.
    createdAt: 'created_at',
    updatedAt: 'updated_at'
});

// Create the association with JobOrder model and define the scope
Notification.belongsTo(JobOrder, {
    as: 'parent',
    foreignKey: 'parent_id', // The column in the "Notification" table that references the "JobOrder" table
    constraints: false, // Set to false if you don't want to enforce foreign key constraints
    scope: {
        [Op.and]: [
            { '$Notification.parent_model$': 'jobOrder' }, // Set the condition for parent_model = 'jobOrder'
        ],
    },
});

module.exports = Notification;
