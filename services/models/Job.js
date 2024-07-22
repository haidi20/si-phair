const { Sequelize, DataTypes } = require('sequelize');
const db = require('../database');

const Job = db.define('Job', {
    id: {
        type: DataTypes.BIGINT,
        autoIncrement: true,
        primaryKey: true,
    },
    code: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    name: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    description: {
        type: DataTypes.TEXT,
        allowNull: true,
    },
    created_by: {
        type: DataTypes.BIGINT,
        allowNull: true,
    },
    updated_by: {
        type: DataTypes.BIGINT,
        allowNull: true,
    },
    deleted_by: {
        type: DataTypes.BIGINT,
        allowNull: true,
    },
    created_at: {
        type: DataTypes.DATE,
        allowNull: true,
    },
    updated_at: {
        type: DataTypes.DATE,
        allowNull: true,
    },
    deleted_at: {
        type: DataTypes.DATE,
        allowNull: true,
    },
}, {
    tableName: 'jobs',
    underscored: true,
    timestamps: false,
});

module.exports = Job;
