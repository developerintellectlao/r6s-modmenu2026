import { useEffect, useState } from "@wordpress/element";
import { usePosts } from "../hooks";

import { BlockSection, PostCard } from "../components";

const DEFAULT_QUERY = {
    per_page: 100,
    orderby: "date",
    order: "desc",
};

const RecentPosts = ({ attributes }) => {

    const {
        recentPostLabel: sectionLabel,
        recentTitleSelector: titleSelector,
        recentPostCount: numberOfPosts,
        recentPostShowThumbnail: showPostThumbnail,
        recentPostShowDate: showDate,
        layoutStyle,
        openInNewTab,
    } = attributes;

    const [posts, setPosts] = useState([]);

    const allPosts = usePosts(DEFAULT_QUERY);

    useEffect(() => {
        setPosts(allPosts);
    }, [allPosts]);

    return <BlockSection id="rishi_recent_posts" className="rishi_sidebar_widget_recent_post" titleSelector={titleSelector} sectionLabel={sectionLabel} layoutStyle={layoutStyle}>
        {posts?.slice(0, numberOfPosts)?.map((item, index) => {
            return <PostCard key={index} item={{
                ...item,
                showPostThumbnail,
                showDate,
                openInNewTab,
            }}
            />
        })}
    </BlockSection>
}

export default RecentPosts;
