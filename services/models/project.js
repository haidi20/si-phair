const { Sequelize, DataTypes } = require('sequelize');
const db = require('../database'); // Replace '../database' with the path to your Sequelize instance

const Project = db.define('Project', {
    id: {
        type: DataTypes.BIGINT,
        autoIncrement: true,
        primaryKey: true,
    },
    location_id: {
        type: DataTypes.BIGINT,
        allowNull: false,
    },
    foreman_id: {
        type: DataTypes.BIGINT,
        defaultValue: null,
    },
    barge_id: {
        type: DataTypes.BIGINT,
        defaultValue: null,
    },
    name: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    date_end: {
        type: DataTypes.DATEONLY,
        defaultValue: null,
    },
    day_duration: {
        type: DataTypes.INTEGER,
        defaultValue: null,
    },
    price: {
        type: DataTypes.DOUBLE,
        defaultValue: null,
    },
    down_payment: {
        type: DataTypes.DOUBLE,
        defaultValue: null,
    },
    remaining_payment: {
        type: DataTypes.DOUBLE,
        defaultValue: null,
    },
    type: {
        type: DataTypes.ENUM('contract', 'daily'),
        defaultValue: null,
    },
    note: {
        type: DataTypes.STRING,
        defaultValue: null,
    },
    created_by: {
        type: DataTypes.BIGINT,
        defaultValue: null,
    },
    updated_by: {
        type: DataTypes.BIGINT,
        defaultValue: null,
    },
    deleted_by: {
        type: DataTypes.BIGINT,
        defaultValue: null,
    },
    created_at: {
        type: DataTypes.DATE,
        defaultValue: null,
    },
    updated_at: {
        type: DataTypes.DATE,
        defaultValue: null,
    },
    deleted_at: {
        type: DataTypes.DATE,
        defaultValue: null,
    },
    date_start: {
        type: DataTypes.DATEONLY,
        defaultValue: null,
    },
}, {
    tableName: 'projects',
    underscored: true,
    timestamps: false,
});

module.exports = Project;
