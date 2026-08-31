<?php

/**
 * Built-in Lightweight Curriculum & Reference Data for:
 * - English (Cambridge A2 Key Essential Vocab & Structures)
 * - Chinese (Pinyin Chart, HSK 1-3 Core Vocab & Grammar)
 * - Japanese (Hiragana, Katakana, JLPT N5 Vocab & Kanji)
 * - Korean (Hangul Alphabet, TOPIK I Core Vocab & Expressions)
 */

function get_pinyin_data(): array
{
    return [
        'initials' => [
            ['p' => 'b', 'ipa' => 'b', 'desc' => 'như p tiếng Việt (bà -> p)'],
            ['p' => 'p', 'ipa' => 'pʰ', 'desc' => 'bật hơi mạnh (ph)'],
            ['p' => 'm', 'ipa' => 'm', 'desc' => 'như m tiếng Việt'],
            ['p' => 'f', 'ipa' => 'f', 'desc' => 'như ph/f tiếng Việt'],
            ['p' => 'd', 'ipa' => 't', 'desc' => 'như t tiếng Việt'],
            ['p' => 't', 'ipa' => 'tʰ', 'desc' => 'như th tiếng Việt, bật hơi'],
            ['p' => 'n', 'ipa' => 'n', 'desc' => 'như n tiếng Việt'],
            ['p' => 'l', 'ipa' => 'l', 'desc' => 'như l tiếng Việt'],
            ['p' => 'g', 'ipa' => 'k', 'desc' => 'như c/k tiếng Việt'],
            ['p' => 'k', 'ipa' => 'kʰ', 'desc' => 'như kh tiếng Việt, bật hơi'],
            ['p' => 'h', 'ipa' => 'x', 'desc' => 'như h/kh nhẹ'],
            ['p' => 'j', 'ipa' => 'tɕ', 'desc' => 'mặt lưỡi, như ch/gi nhẹ'],
            ['p' => 'q', 'ipa' => 'tɕʰ', 'desc' => 'mặt lưỡi bật hơi mạnh'],
            ['p' => 'x', 'ipa' => 'ɕ', 'desc' => 'như x tiếng Việt'],
            ['p' => 'zh', 'ipa' => 'ʈʂ', 'desc' => 'uốn lưỡi, như tr tiếng Việt'],
            ['p' => 'ch', 'ipa' => 'ʈʂʰ', 'desc' => 'uốn lưỡi + bật hơi'],
            ['p' => 'sh', 'ipa' => 'ʂ', 'desc' => 'uốn lưỡi, như s tiếng Việt'],
            ['p' => 'r', 'ipa' => 'ʐ', 'desc' => 'uốn lưỡi, như r tiếng Việt'],
            ['p' => 'z', 'ipa' => 'ts', 'desc' => 'đầu lưỡi thẳng, như ch/tz'],
            ['p' => 'c', 'ipa' => 'tsʰ', 'desc' => 'đầu lưỡi thẳng + bật hơi'],
            ['p' => 's', 'ipa' => 's', 'desc' => 'đầu lưỡi thẳng, như s/x'],
        ],
        'finals' => [
            ['p' => 'a', 'ipa' => 'a', 'desc' => 'a'],
            ['p' => 'o', 'ipa' => 'o', 'desc' => 'ô / uô'],
            ['p' => 'e', 'ipa' => 'ɤ', 'desc' => 'ưa / ơ'],
            ['p' => 'i', 'ipa' => 'i', 'desc' => 'i (hoặc ư sau z, c, s, zh, ch, sh, r)'],
            ['p' => 'u', 'ipa' => 'u', 'desc' => 'u'],
            ['p' => 'ü', 'ipa' => 'y', 'desc' => 'uy (tròn môi)'],
            ['p' => 'ai', 'ipa' => 'ai', 'desc' => 'ai'],
            ['p' => 'ei', 'ipa' => 'ei', 'desc' => 'ây'],
            ['p' => 'ao', 'ipa' => 'au', 'desc' => 'ao'],
            ['p' => 'ou', 'ipa' => 'ou', 'desc' => 'âu / ôu'],
            ['p' => 'an', 'ipa' => 'an', 'desc' => 'an'],
            ['p' => 'en', 'ipa' => 'ən', 'desc' => 'ân'],
            ['p' => 'ang', 'ipa' => 'aŋ', 'desc' => 'ang'],
            ['p' => 'eng', 'ipa' => 'əŋ', 'desc' => 'âng'],
            ['p' => 'er', 'ipa' => 'aɚ', 'desc' => 'ơ (uốn lưỡi)'],
        ],
        'tones' => [
            ['tone' => 'Thanh 1 (Âm Bình)', 'mark' => 'ā', 'pitch' => '55', 'desc' => 'Cao bằng, ngân đều: mā (mẹ)'],
            ['tone' => 'Thanh 2 (Dương Bình)', 'mark' => 'á', 'pitch' => '35', 'desc' => 'Từ trung bình lên cao (như dấu sắc): má (gai)'],
            ['tone' => 'Thanh 3 (Thượng Thanh)', 'mark' => 'ǎ', 'pitch' => '214', 'desc' => 'Xuống thấp rồi lên nhẹ (như hỏi+ngã): mǎ (ngựa)'],
            ['tone' => 'Thanh 4 (Khứ Thanh)', 'mark' => 'à', 'pitch' => '51', 'desc' => 'Từ cao rơi mạnh dứt khoát: mà (mắng)'],
            ['tone' => 'Thanh nhẹ (Khinh Thanh)', 'mark' => 'ma', 'pitch' => '--', 'desc' => 'Đọc ngắn nhẹ: ma (trợ từ)'],
        ]
    ];
}

function get_kana_data(): array
{
    return [
        'hiragana' => [
            ['h' => 'あ', 'r' => 'a', 'k' => 'ア'], ['h' => 'い', 'r' => 'i', 'k' => 'イ'], ['h' => 'う', 'r' => 'u', 'k' => 'ウ'], ['h' => 'え', 'r' => 'e', 'k' => 'エ'], ['h' => 'お', 'r' => 'o', 'k' => 'オ'],
            ['h' => 'か', 'r' => 'ka', 'k' => 'カ'], ['h' => 'き', 'r' => 'ki', 'k' => 'キ'], ['h' => 'く', 'r' => 'ku', 'k' => 'ク'], ['h' => 'け', 'r' => 'ke', 'k' => 'ケ'], ['h' => 'こ', 'r' => 'ko', 'k' => 'コ'],
            ['h' => 'さ', 'r' => 'sa', 'k' => 'サ'], ['h' => 'し', 'r' => 'shi', 'k' => 'シ'], ['h' => 'す', 'r' => 'su', 'k' => 'ス'], ['h' => 'せ', 'r' => 'se', 'k' => 'セ'], ['h' => 'そ', 'r' => 'so', 'k' => 'ソ'],
            ['h' => 'た', 'r' => 'ta', 'k' => 'タ'], ['h' => 'ち', 'r' => 'chi', 'k' => 'チ'], ['h' => 'つ', 'r' => 'tsu', 'k' => 'ツ'], ['h' => 'て', 'r' => 'te', 'k' => 'テ'], ['h' => 'と', 'r' => 'to', 'k' => 'ト'],
            ['h' => 'な', 'r' => 'na', 'k' => 'ナ'], ['h' => 'に', 'r' => 'ni', 'k' => 'ニ'], ['h' => 'ぬ', 'r' => 'nu', 'k' => 'ヌ'], ['h' => 'ね', 'r' => 'ne', 'k' => 'ネ'], ['h' => 'の', 'r' => 'no', 'k' => 'ノ'],
            ['h' => 'は', 'r' => 'ha', 'k' => 'ハ'], ['h' => 'ひ', 'r' => 'hi', 'k' => 'ヒ'], ['h' => 'ふ', 'r' => 'fu', 'k' => 'フ'], ['h' => 'へ', 'r' => 'he', 'k' => 'ヘ'], ['h' => 'ほ', 'r' => 'ho', 'k' => 'ホ'],
            ['h' => 'ま', 'r' => 'ma', 'k' => 'マ'], ['h' => 'み', 'r' => 'mi', 'k' => 'ミ'], ['h' => 'む', 'r' => 'mu', 'k' => 'ム'], ['h' => 'め', 'r' => 'me', 'k' => 'メ'], ['h' => 'も', 'r' => 'mo', 'k' => 'モ'],
            ['h' => 'や', 'r' => 'ya', 'k' => 'ヤ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'ゆ', 'r' => 'yu', 'k' => 'ユ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'よ', 'r' => 'yo', 'k' => 'ヨ'],
            ['h' => 'ら', 'r' => 'ra', 'k' => 'ラ'], ['h' => 'り', 'r' => 'ri', 'k' => 'リ'], ['h' => 'る', 'r' => 'ru', 'k' => 'ル'], ['h' => 'れ', 'r' => 're', 'k' => 'レ'], ['h' => 'ろ', 'r' => 'ro', 'k' => 'ロ'],
            ['h' => 'わ', 'r' => 'wa', 'k' => 'ワ'], ['h' => '', 'r' => '', 'k' => ''], ['h' => '', 'r' => '', 'k' => ''], ['h' => '', 'r' => '', 'k' => ''], ['h' => 'を', 'r' => 'wo', 'k' => 'ヲ'],
            ['h' => 'ん', 'r' => 'n', 'k' => 'ン'],
        ],
        'dakuten' => [
            ['h' => 'が', 'r' => 'ga'], ['h' => 'ぎ', 'r' => 'gi'], ['h' => 'ぐ', 'r' => 'gu'], ['h' => 'げ', 'r' => 'ge'], ['h' => 'ご', 'r' => 'go'],
            ['h' => 'ざ', 'r' => 'za'], ['h' => 'じ', 'r' => 'ji'], ['h' => 'ず', 'r' => 'zu'], ['h' => 'ぜ', 'r' => 'ze'], ['h' => 'ぞ', 'r' => 'zo'],
            ['h' => 'だ', 'r' => 'da'], ['h' => 'ぢ', 'r' => 'ji/dji'], ['h' => 'づ', 'r' => 'zu/dzu'], ['h' => 'で', 'r' => 'de'], ['h' => 'ど', 'r' => 'do'],
            ['h' => 'ば', 'r' => 'ba'], ['h' => 'び', 'r' => 'bi'], ['h' => 'ぶ', 'r' => 'bu'], ['h' => 'べ', 'r' => 'be'], ['h' => 'ぼ', 'r' => 'bo'],
            ['h' => 'ぱ', 'r' => 'pa'], ['h' => 'ぴ', 'r' => 'pi'], ['h' => 'ぷ', 'r' => 'pu'], ['h' => 'ぺ', 'r' => 'pe'], ['h' => 'ぽ', 'r' => 'po'],
        ]
    ];
}

function get_hangul_data(): array
{
    return [
        'vowels' => [
            ['c' => 'ㅏ', 'r' => 'a', 'desc' => 'a (như ba)'],
            ['c' => 'ㅑ', 'r' => 'ya', 'desc' => 'ya'],
            ['c' => 'ㅓ', 'r' => 'eo', 'desc' => 'ơ / o (mở rộng miệng)'],
            ['c' => 'ㅕ', 'r' => 'yeo', 'desc' => 'yơ / yo'],
            ['c' => 'ㅗ', 'r' => 'o', 'desc' => 'ô (tròn môi)'],
            ['c' => 'ㅛ', 'r' => 'yo', 'desc' => 'yô'],
            ['c' => 'ㅜ', 'r' => 'u', 'desc' => 'u (tròn môi)'],
            ['c' => 'ㅠ', 'r' => 'yu', 'desc' => 'yu'],
            ['c' => 'ㅡ', 'r' => 'eu', 'desc' => 'ư (dẹt miệng)'],
            ['c' => 'ㅣ', 'r' => 'i', 'desc' => 'i'],
            ['c' => 'ㅐ', 'r' => 'ae', 'desc' => 'e / ae'],
            ['c' => 'ㅔ', 'r' => 'e', 'desc' => 'ê / e'],
            ['c' => 'ㅘ', 'r' => 'wa', 'desc' => 'oa'],
            ['c' => 'ㅝ', 'r' => 'wo', 'desc' => 'uơ'],
            ['c' => 'ㅟ', 'r' => 'wi', 'desc' => 'uy'],
            ['c' => 'ㅢ', 'r' => 'ui', 'desc' => 'ưi'],
        ],
        'consonants' => [
            ['c' => 'ㄱ', 'r' => 'g/k', 'desc' => 'khi đứng đầu là k, giữa từ là g'],
            ['c' => 'ㄴ', 'r' => 'n', 'desc' => 'âm n'],
            ['c' => 'ㄷ', 'r' => 'd/t', 'desc' => 'khi đứng đầu là t, giữa từ là d'],
            ['c' => 'ㄹ', 'r' => 'r/l', 'desc' => 'đầu từ là r, cuối âm là l'],
            ['c' => 'ㅁ', 'r' => 'm', 'desc' => 'âm m'],
            ['c' => 'ㅂ', 'r' => 'b/p', 'desc' => 'đầu từ là p, giữa từ là b'],
            ['c' => 'ㅅ', 'r' => 's', 'desc' => 'âm s nhẹ (với i đọc là sh)'],
            ['c' => 'ㅇ', 'r' => 'ng / silent', 'desc' => 'đầu từ là câm (chỉ đệm âm), cuối từ là ng'],
            ['c' => 'ㅈ', 'r' => 'j/ch', 'desc' => 'âm ch nhẹ / j'],
            ['c' => 'ㅊ', 'r' => 'ch', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅋ', 'r' => 'k', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅌ', 'r' => 't', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅍ', 'r' => 'p', 'desc' => 'bật hơi mạnh'],
            ['c' => 'ㅎ', 'r' => 'h', 'desc' => 'âm h'],
            ['c' => 'ㄲ', 'r' => 'kk', 'desc' => 'âm căng k'],
            ['c' => 'ㄸ', 'r' => 'tt', 'desc' => 'âm căng t'],
            ['c' => 'ㅃ', 'r' => 'pp', 'desc' => 'âm căng p'],
            ['c' => 'ㅆ', 'r' => 'ss', 'desc' => 'âm căng s'],
            ['c' => 'ㅉ', 'r' => 'jj', 'desc' => 'âm căng ch/j'],
        ]
    ];
}

function get_language_vocab_packs(): array
{
    return [
        'en' => [
            [
                'category' => 'A2 Key Essential (Daily Life & School)',
                'level' => 'A2',
                'items' => [
                    ['word' => 'accommodation', 'ipa' => '/əˌkɒm.əˈdeɪ.ʃən/', 'meaning' => 'chỗ ở, nơi trọ', 'example' => 'We need to find cheap accommodation near the university.'],
                    ['word' => 'appointment', 'ipa' => '/əˈpɔɪnt.mənt/', 'meaning' => 'cuộc hẹn', 'example' => 'I have an appointment with the dentist at 3 PM.'],
                    ['word' => 'attraction', 'ipa' => '/əˈtræk.ʃən/', 'meaning' => 'điểm thu hút du lịch', 'example' => 'The castle is the main tourist attraction in our town.'],
                    ['word' => 'available', 'ipa' => '/əˈveɪ.lə.bəl/', 'meaning' => 'có sẵn, rảnh rỗi', 'example' => 'Are tickets available for tomorrow night?'],
                    ['word' => 'borrow', 'ipa' => '/ˈbɒr.əʊ/', 'meaning' => 'mượn', 'example' => 'Can I borrow your English dictionary for a minute?'],
                    ['word' => 'celebrate', 'ipa' => '/ˈsel.ə.breɪt/', 'meaning' => 'ăn mừng, kỷ niệm', 'example' => 'We are going to celebrate my brother’s birthday tomorrow.'],
                    ['word' => 'competition', 'ipa' => '/ˌkɒm.pəˈtɪʃ.ən/', 'meaning' => 'cuộc thi', 'example' => 'She won first prize in the photography competition.'],
                    ['word' => 'delicious', 'ipa' => '/dɪˈlɪʃ.əs/', 'meaning' => 'ngon miệng', 'example' => 'This noodle soup is absolutely delicious.'],
                    ['word' => 'equipment', 'ipa' => '/ɪˈkwɪp.mənt/', 'meaning' => 'trang thiết bị', 'example' => 'You need special equipment for rock climbing.'],
                    ['word' => 'invitation', 'ipa' => '/ˌɪn.vɪˈteɪ.ʃən/', 'meaning' => 'lời mời, thiệp mời', 'example' => 'Thanks for the invitation to your concert!'],
                    ['word' => 'opportunity', 'ipa' => '/ˌɒp.əˈtjuː.nə.ti/', 'meaning' => 'cơ hội', 'example' => 'Studying abroad is a great opportunity to learn.'],
                    ['word' => 'recommend', 'ipa' => '/ˌrek.əˈmend/', 'meaning' => 'gợi ý, khuyên dùng', 'example' => 'Can you recommend a good restaurant nearby?'],
                ]
            ],
            [
                'category' => 'A2 Key Travel & Transport',
                'level' => 'A2-B1',
                'items' => [
                    ['word' => 'delay', 'ipa' => '/dɪˈleɪ/', 'meaning' => 'trì hoãn, chậm trễ', 'example' => 'The flight had a two-hour delay due to bad weather.'],
                    ['word' => 'destination', 'ipa' => '/ˌdes.tɪˈneɪ.ʃən/', 'meaning' => 'điểm đến', 'example' => 'Da Nang is our favorite holiday destination.'],
                    ['word' => 'luggage', 'ipa' => '/ˈlʌɡ.ɪdʒ/', 'meaning' => 'hành lý', 'example' => 'Do not leave your luggage unattended at the station.'],
                    ['word' => 'passenger', 'ipa' => '/ˈpæs.ən.dʒər/', 'meaning' => 'hành khách', 'example' => 'All passengers must fasten their seatbelts.'],
                    ['word' => 'platform', 'ipa' => '/ˈplæt.fɔːm/', 'meaning' => 'sân ga, thềm ga', 'example' => 'The train to London departs from platform 4.'],
                ]
            ]
        ],
        'zh' => [
            [
                'category' => 'HSK 1 - Chào hỏi & Giao tiếp cơ bản',
                'level' => 'HSK 1',
                'items' => [
                    ['word' => '你好', 'ipa' => 'Nǐ hǎo', 'meaning' => 'Xin chào (Hán Việt: Nhĩ Hảo)', 'example' => '你好！很高兴认识你。(Nǐ hǎo! Hěn gāoxìng rènshí nǐ.)'],
                    ['word' => '谢谢', 'ipa' => 'Xièxie', 'meaning' => 'Cảm ơn (Hán Việt: Tạ Tạ)', 'example' => '非常感谢你的帮助！(Fēicháng gǎnxiè nǐ de bāngzhù!)'],
                    ['word' => '不客气', 'ipa' => 'Bù kèqi', 'meaning' => 'Không có chi / Đừng khách sáo', 'example' => '不客气，这是我应该做的。(Bù kèqi, zhè shì wǒ yīnggāi zuò de.)'],
                    ['word' => '再见', 'ipa' => 'Zàijiàn', 'meaning' => 'Tạm biệt (Hán Việt: Tái Kiến)', 'example' => '明天见，再见！(Míngtiān jiàn, zàijiàn!)'],
                    ['word' => '学习', 'ipa' => 'Xuéxí', 'meaning' => 'Học tập (Hán Việt: Học Tập)', 'example' => '我在学习汉语。(Wǒ zài xuéxí hànyǔ.)'],
                    ['word' => '汉语', 'ipa' => 'Hànyǔ', 'meaning' => 'Tiếng Hán / Tiếng Trung', 'example' => '汉语很有趣。(Hànyǔ hěn yǒuqù.)'],
                    ['word' => '朋友', 'ipa' => 'Péngyou', 'meaning' => 'Bạn bè (Hán Việt: Bằng Hữu)', 'example' => '他是我的好朋友。(Tā shì wǒ de hǎo péngyou.)'],
                    ['word' => '多少钱', 'ipa' => 'Duōshao qián', 'meaning' => 'Bao nhiêu tiền?', 'example' => '这个苹果多少钱一斤？(Zhège píngguǒ duōshao qián yī jīn?)'],
                ]
            ],
            [
                'category' => 'HSK 2 & 3 - Đời sống & Du lịch',
                'level' => 'HSK 2-3',
                'items' => [
                    ['word' => '高兴', 'ipa' => 'Gāoxìng', 'meaning' => 'Vui mừng (Cao Hứng)', 'example' => '今天大家都很高兴。(Jīntiān dàjiā dōu hěn gāoxìng.)'],
                    ['word' => '机场', 'ipa' => 'Jīchǎng', 'meaning' => 'Sân bay (Cơ Trường)', 'example' => '我们去机场接朋友。(Wǒmen qù jīchǎng jiē péngyou.)'],
                    ['word' => '准备', 'ipa' => 'Zhǔnbèi', 'meaning' => 'Chuẩn bị (Chuẩn Bị)', 'example' => '你准备好考试了吗？(Nǐ zhǔnbèi hǎo kǎoshì le ma?)'],
                    ['word' => '帮助', 'ipa' => 'Bāngzhù', 'meaning' => 'Giúp đỡ (Bang Trợ)', 'example' => '谢谢你的热情帮助。(Xièxie nǐ de rèqíng bāngzhù.)'],
                ]
            ]
        ],
        'ja' => [
            [
                'category' => 'JLPT N5 - Chào hỏi & Sinh hoạt thường ngày',
                'level' => 'N5',
                'items' => [
                    ['word' => 'こんにちは', 'ipa' => 'Konnichiwa', 'meaning' => 'Xin chào (ban ngày)', 'example' => 'こんにちは、お元気ですか。(Konnichiwa, ogenki desu ka.)'],
                    ['word' => 'ありがとう', 'ipa' => 'Arigatou', 'meaning' => 'Cảm ơn', 'example' => 'どうもありがとうございます。(Doumo arigatou gozaimasu.)'],
                    ['word' => '先生', 'ipa' => 'Sensei (Tiên Sinh)', 'meaning' => 'Thầy cô giáo / Bác sĩ', 'example' => '日本語の先生です。(Nihongo no sensei desu.)'],
                    ['word' => '日本語', 'ipa' => 'Nihongo (Nhật Bản Ngữ)', 'meaning' => 'Tiếng Nhật', 'example' => '毎日日本語を勉強します。(Mainichi nihongo o benkyou shimasu.)'],
                    ['word' => '食べる', 'ipa' => 'Taberu (Thực)', 'meaning' => 'Ăn', 'example' => '朝ごはんを食べます。(Asagohan o tabemasu.)'],
                    ['word' => '飲む', 'ipa' => 'Nomu (Ẩm)', 'meaning' => 'Uống', 'example' => 'お茶を飲みます。(Ocha o nomimasu.)'],
                    ['word' => '友達', 'ipa' => 'Tomodachi (Hữu Đạt)', 'meaning' => 'Bạn bè', 'example' => '友達と映画を見ました。(Tomodachi to eiga o mimashita.)'],
                    ['word' => '行く', 'ipa' => 'Iku (Hành)', 'meaning' => 'Đi', 'example' => '学校へ行きます。(Gakkou e ikimasu.)'],
                ]
            ],
            [
                'category' => 'JLPT N5 - N4 - Kanji Cơ Bản & Mẫu Câu',
                'level' => 'N5-N4',
                'items' => [
                    ['word' => '約束', 'ipa' => 'Yakusoku (Ước Thúc)', 'meaning' => 'Lời hứa / Cuộc hẹn', 'example' => '友達と約束があります。(Tomodachi to yakusoku ga arimasu.)'],
                    ['word' => '大丈夫', 'ipa' => 'Daijoubu (Đại Trượng Phu)', 'meaning' => 'Ổn / Không sao', 'example' => '大丈夫ですから、心配しないで。(Daijoubu desu kara, shinpai shinaide.)'],
                    ['word' => '便利', 'ipa' => 'Benri (Tiện Lợi)', 'meaning' => 'Tiện lợi', 'example' => '地下鉄はとても便利です。(Chikatetsu wa totemo benri desu.)'],
                ]
            ]
        ],
        'ko' => [
            [
                'category' => 'TOPIK I - Chào hỏi & Giao tiếp thông dụng',
                'level' => 'Sơ cấp 1',
                'items' => [
                    ['word' => '안녕하세요', 'ipa' => 'An-nyeong-ha-se-yo', 'meaning' => 'Xin chào (lịch sự)', 'example' => '안녕하세요! 반갑습니다. (Xin chào! Rất vui được gặp bạn.)'],
                    ['word' => '감사합니다', 'ipa' => 'Gam-sa-ham-ni-da', 'meaning' => 'Cảm ơn (trang trọng)', 'example' => '도와주셔서 정말 감사합니다. (Thật lòng cảm ơn vì đã giúp đỡ.)'],
                    ['word' => '죄송합니다', 'ipa' => 'Joe-song-ham-ni-da', 'meaning' => 'Xin lỗi (trang trọng)', 'example' => '늦어서 죄송합니다. (Xin lỗi vì tôi đến muộn.)'],
                    ['word' => '한국어', 'ipa' => 'Han-guk-eo', 'meaning' => 'Tiếng Hàn Quốc', 'example' => '저는 한국어를 배워요. (Tôi học tiếng Hàn.)'],
                    ['word' => '친구', 'ipa' => 'Chin-gu', 'meaning' => 'Bạn bè', 'example' => '친구와 같이 밥을 먹어요. (Ăn cơm cùng bạn.)'],
                    ['word' => '사랑해요', 'ipa' => 'Sa-rang-hae-yo', 'meaning' => 'Tôi yêu bạn', 'example' => '가족을 많이 사랑해요. (Tôi rất yêu gia đình.)'],
                    ['word' => '맛있어요', 'ipa' => 'Mas-iss-eo-yo', 'meaning' => 'Ngon miệng', 'example' => '이 김치찌개는 정말 맛있어요. (Món canh kim chi này rất ngon.)'],
                    ['word' => '얼마예요', 'ipa' => 'Eol-ma-ye-yo', 'meaning' => 'Bao nhiêu tiền?', 'example' => '이거 얼마예요? (Cái này giá bao nhiêu ạ?)'],
                ]
            ],
            [
                'category' => 'TOPIK I - Động từ & Tính từ cơ bản',
                'level' => 'Sơ cấp 2',
                'items' => [
                    ['word' => '가다 / 와요', 'ipa' => 'Ga-da / Wa-yo', 'meaning' => 'Đi / Đến', 'example' => '집에 가요. 학교에 와요.'],
                    ['word' => '공부하다', 'ipa' => 'Gong-bu-ha-da', 'meaning' => 'Học tập', 'example' => '도서관에서 공부해요. (Học ở thư viện.)'],
                    ['word' => '행복하다', 'ipa' => 'Haeng-bok-ha-da', 'meaning' => 'Hạnh phúc', 'example' => '오늘 정말 행복해요. (Hôm nay tôi rất hạnh phúc.)'],
                ]
            ]
        ]
    ];
}
