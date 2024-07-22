const moment = require('moment');
const express = require('express');

const JobOrder = require("../models/jobOrder");
const { Op, literal } = require('sequelize');
const Project = require('../models/project');
const sequelize = require('../database');
const Job = require('../models/Job');

exports.getJobOrderNotFinish = async ({ now, userId }) => {
    let jobOrders = [];
    const time = 15;

    jobOrders = await JobOrder.findAll({ // Include the virtual column here
        where: {
            [Op.and]: [
                literal(`DATE_FORMAT(DATE_SUB(datetime_estimation_end, INTERVAL ${time} MINUTE), '%Y-%m-%d %H:%i:%s') <= '${now}'`),
                { status: 'active' }, // Filter by status = 'active'
                { created_by: userId },
            ],
        },
        include: [
            {
                model: Project,
                as: 'project',
            },
            {
                model: Job,
                as: 'job',
            },
        ],
        order: [
            ['datetime_estimation_end', 'ASC']
        ],
        limit: 5,
    });

    return jobOrders;
}
