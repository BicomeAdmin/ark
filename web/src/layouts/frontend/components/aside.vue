<template>
    <el-aside class="ba-user-layouts">
        <div class="userinfo">
            <div @click="routerPush('account/profile')" class="user-avatar-box">
                <img class="user-avatar" :src="fullUrl(userInfo.avatar ? userInfo.avatar : '/static/images/avatar.png')" alt="" />
                <Icon class="user-avatar-gender" :name="userInfo.getGenderIcon()['name']" size="14" :color="userInfo.getGenderIcon()['color']" />
            </div>
            <p class="username">{{ userInfo.nickname }}</p>
            <el-button-group>
                <el-button
                    @click="routerPush('account/integral')"
                    v-blur
                    class="userinfo-button-item"
                    :title="$t('Integral') + ' ' + userInfo.score"
                    size="default"
                    plain
                >
                    <span>{{ $t('Integral') + ' ' + userInfo.score }}</span>
                </el-button>
                <el-button
                    @click="routerPush('account/balance')"
                    v-blur
                    class="userinfo-button-item"
                    :title="$t('Balance') + ' ' + userInfo.money"
                    size="default"
                    plain
                >
                    <span>{{ $t('Balance') + ' ' + userInfo.money }}</span>
                </el-button>
            </el-button-group>
        </div>

        <div class="user-menus">
            <template v-for="(item, idx) in memberCenter.state.viewRoutes" :key="idx">
                <div v-if="memberCenter.state.showHeadline" class="user-menu-max-title">{{ item.meta?.title }}</div>
                <div
                    v-for="(menu, index) in item.children"
                    :key="index"
                    @click="routerPush(menu)"
                    class="user-menu-item"
                    :class="route.fullPath == menu.path ? 'active' : ''"
                >
                    <Icon :name="menu.meta?.icon" size="16" color="var(--el-text-color-secondary)" />
                    <span>{{ menu.meta?.title }}</span>
                </div>
            </template>
        </div>
    </el-aside>
</template>

<script setup lang="ts">
import { useRoute, useRouter, type RouteRecordRaw } from 'vue-router'
import { useMemberCenter } from '/@/stores/memberCenter'
import { useUserInfo } from '/@/stores/userInfo'
import { fullUrl } from '/@/utils/common'
import { onClickMenu } from '/@/utils/router'

const route = useRoute()
const router = useRouter()
const userInfo = useUserInfo()
const memberCenter = useMemberCenter()

const routerPush = (route: string | RouteRecordRaw) => {
    if (typeof route === 'string') {
        router.push({ name: route })
    } else {
        onClickMenu(route)
    }
}
</script>

<style scoped lang="scss">
.ba-user-layouts {
    width: 240px;
    background-color: var(--ba-bg-color-overlay);
    box-shadow: var(--el-box-shadow-light);
}
.userinfo {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}
.username {
    display: block;
    text-align: center;
    width: 100%;
    padding: 10px 0;
    font-size: var(--el-font-size-large);
    font-weight: bold;
}
.user-avatar-box {
    position: relative;
    width: 100px;
    height: 100px;
    cursor: pointer;
}
.user-avatar {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.user-avatar-gender {
    position: absolute;
    bottom: 0;
    right: 10px;
    height: 22px;
    width: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--ba-bg-color-overlay);
    border-radius: 50%;
    box-shadow: var(--el-box-shadow);
}
.userinfo-button-item {
    font-size: var(--el-font-size-small);
    height: 30px;
}
.user-menus {
    font-size: var(--el-font-size-base);
    color: var(--el-text-color-regular);
    padding-bottom: 20px;
}
.user-menu-max-title {
    font-size: 15px;
    color: var(--el-text-color-secondary);
    padding: 5px 30px;
}
.user-menu-item {
    padding: 10px 30px;
    cursor: pointer;
    .icon {
        width: 16px;
        height: 16px;
        text-align: center;
        margin-right: 8px;
    }
}
.user-menu-item:hover,
.user-menu-item.active {
    border-left: 2px solid var(--el-color-primary);
    padding-left: 28px;
    color: var(--el-color-primary);
    .icon {
        color: var(--el-color-primary) !important;
    }
    background-color: var(--el-color-info-light-8);
}
@media screen and (max-width: 991px) {
    .ba-user-layouts {
        width: 100%;
        background-color: var(--ba-bg-color-overlay);
        box-shadow: none;
    }
}
</style>
